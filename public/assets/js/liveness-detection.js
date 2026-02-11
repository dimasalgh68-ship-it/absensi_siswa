/**
 * Liveness Detection Module
 * Detects blinks and head movements for anti-spoofing
 */

class LivenessDetector {
    constructor() {
        this.blinkCount = 0;
        this.blinkThreshold = 0.25; // Eye aspect ratio threshold
        this.consecutiveFrames = 3; // Frames needed to confirm blink
        this.blinkFrameCounter = 0;
        this.eyeWasOpen = true;
        
        this.headPositions = [];
        this.maxHeadPositions = 10;
        this.headMovementThreshold = 15; // degrees
        
        this.isActive = false;
        this.startTime = null;
        this.requiredBlinks = 2;
        this.requiredHeadMovement = true;
        this.timeLimit = 10000; // 10 seconds
        
        this.callbacks = {
            onBlinkDetected: null,
            onHeadMovementDetected: null,
            onLivenessConfirmed: null,
            onLivenessFailed: null,
            onProgress: null
        };
    }

    /**
     * Start liveness detection
     */
    start(options = {}) {
        this.reset();
        this.isActive = true;
        this.startTime = Date.now();
        
        if (options.requiredBlinks !== undefined) {
            this.requiredBlinks = options.requiredBlinks;
        }
        if (options.requiredHeadMovement !== undefined) {
            this.requiredHeadMovement = options.requiredHeadMovement;
        }
        if (options.timeLimit !== undefined) {
            this.timeLimit = options.timeLimit;
        }
        
        console.log('Liveness detection started', {
            requiredBlinks: this.requiredBlinks,
            requiredHeadMovement: this.requiredHeadMovement,
            timeLimit: this.timeLimit
        });
    }

    /**
     * Stop liveness detection
     */
    stop() {
        this.isActive = false;
        console.log('Liveness detection stopped');
    }

    /**
     * Reset detection state
     */
    reset() {
        this.blinkCount = 0;
        this.blinkFrameCounter = 0;
        this.eyeWasOpen = true;
        this.headPositions = [];
        this.startTime = null;
    }

    /**
     * Process face detection result
     */
    async process(detection) {
        if (!this.isActive || !detection) return;

        // Check timeout
        if (Date.now() - this.startTime > this.timeLimit) {
            this.isActive = false;
            if (this.callbacks.onLivenessFailed) {
                this.callbacks.onLivenessFailed('Waktu habis. Silakan coba lagi.');
            }
            return;
        }

        // Detect blinks
        this.detectBlink(detection);

        // Detect head movement
        this.detectHeadMovement(detection);

        // Update progress
        this.updateProgress();

        // Check if liveness confirmed
        this.checkLivenessConfirmed();
    }

    /**
     * Detect eye blink using Eye Aspect Ratio (EAR)
     */
    detectBlink(detection) {
        if (!detection.landmarks) return;

        const landmarks = detection.landmarks.positions;
        
        // Left eye landmarks (36-41)
        const leftEye = [
            landmarks[36], landmarks[37], landmarks[38],
            landmarks[39], landmarks[40], landmarks[41]
        ];
        
        // Right eye landmarks (42-47)
        const rightEye = [
            landmarks[42], landmarks[43], landmarks[44],
            landmarks[45], landmarks[46], landmarks[47]
        ];

        const leftEAR = this.calculateEAR(leftEye);
        const rightEAR = this.calculateEAR(rightEye);
        const avgEAR = (leftEAR + rightEAR) / 2;

        // Detect blink
        if (avgEAR < this.blinkThreshold) {
            // Eye is closed
            this.blinkFrameCounter++;
            
            if (this.blinkFrameCounter >= this.consecutiveFrames && this.eyeWasOpen) {
                // Blink detected!
                this.blinkCount++;
                this.eyeWasOpen = false;
                
                console.log(`Blink detected! Count: ${this.blinkCount}`);
                
                if (this.callbacks.onBlinkDetected) {
                    this.callbacks.onBlinkDetected(this.blinkCount);
                }
            }
        } else {
            // Eye is open
            if (this.blinkFrameCounter >= this.consecutiveFrames) {
                this.eyeWasOpen = true;
            }
            this.blinkFrameCounter = 0;
        }
    }

    /**
     * Calculate Eye Aspect Ratio (EAR)
     */
    calculateEAR(eye) {
        // Vertical distances
        const v1 = this.euclideanDistance(eye[1], eye[5]);
        const v2 = this.euclideanDistance(eye[2], eye[4]);
        
        // Horizontal distance
        const h = this.euclideanDistance(eye[0], eye[3]);
        
        // EAR formula
        const ear = (v1 + v2) / (2.0 * h);
        
        return ear;
    }

    /**
     * Detect head movement using face angle
     */
    detectHeadMovement(detection) {
        if (!detection.landmarks) return;

        const landmarks = detection.landmarks.positions;
        
        // Calculate head pose angles
        const angles = this.calculateHeadPose(landmarks);
        
        // Store position
        this.headPositions.push(angles);
        
        // Keep only recent positions
        if (this.headPositions.length > this.maxHeadPositions) {
            this.headPositions.shift();
        }

        // Check for significant movement
        if (this.headPositions.length >= 5) {
            const movement = this.calculateHeadMovement();
            
            if (movement.yaw > this.headMovementThreshold || 
                movement.pitch > this.headMovementThreshold) {
                
                console.log('Head movement detected:', movement);
                
                if (this.callbacks.onHeadMovementDetected) {
                    this.callbacks.onHeadMovementDetected(movement);
                }
                
                // Clear positions after detecting movement
                this.headPositions = [];
            }
        }
    }

    /**
     * Calculate head pose angles (simplified)
     */
    calculateHeadPose(landmarks) {
        // Use nose tip (30), left eye (36), right eye (45)
        const noseTip = landmarks[30];
        const leftEye = landmarks[36];
        const rightEye = landmarks[45];
        
        // Calculate yaw (left-right rotation)
        const eyeCenter = {
            x: (leftEye.x + rightEye.x) / 2,
            y: (leftEye.y + rightEye.y) / 2
        };
        
        const yaw = Math.atan2(noseTip.x - eyeCenter.x, 100) * (180 / Math.PI);
        
        // Calculate pitch (up-down rotation)
        const pitch = Math.atan2(noseTip.y - eyeCenter.y, 100) * (180 / Math.PI);
        
        return { yaw, pitch, roll: 0 };
    }

    /**
     * Calculate head movement range
     */
    calculateHeadMovement() {
        if (this.headPositions.length < 2) {
            return { yaw: 0, pitch: 0, roll: 0 };
        }

        const yaws = this.headPositions.map(p => p.yaw);
        const pitches = this.headPositions.map(p => p.pitch);
        
        const yawRange = Math.max(...yaws) - Math.min(...yaws);
        const pitchRange = Math.max(...pitches) - Math.min(...pitches);
        
        return {
            yaw: Math.abs(yawRange),
            pitch: Math.abs(pitchRange),
            roll: 0
        };
    }

    /**
     * Calculate Euclidean distance between two points
     */
    euclideanDistance(p1, p2) {
        const dx = p1.x - p2.x;
        const dy = p1.y - p2.y;
        return Math.sqrt(dx * dx + dy * dy);
    }

    /**
     * Update progress callback
     */
    updateProgress() {
        if (!this.callbacks.onProgress) return;

        const elapsed = Date.now() - this.startTime;
        const progress = {
            blinks: this.blinkCount,
            requiredBlinks: this.requiredBlinks,
            headMovement: this.headPositions.length > 0,
            timeRemaining: Math.max(0, this.timeLimit - elapsed),
            percentage: this.calculateProgressPercentage()
        };

        this.callbacks.onProgress(progress);
    }

    /**
     * Calculate overall progress percentage
     */
    calculateProgressPercentage() {
        let progress = 0;
        
        // Blink progress (70%)
        const blinkProgress = Math.min(this.blinkCount / this.requiredBlinks, 1) * 70;
        progress += blinkProgress;
        
        // Head movement progress (30%)
        if (this.requiredHeadMovement) {
            const headProgress = this.headPositions.length >= 5 ? 30 : 0;
            progress += headProgress;
        } else {
            progress += 30; // Skip head movement requirement
        }
        
        return Math.round(progress);
    }

    /**
     * Check if liveness is confirmed
     */
    checkLivenessConfirmed() {
        const blinksPassed = this.blinkCount >= this.requiredBlinks;
        const headMovementPassed = !this.requiredHeadMovement || this.headPositions.length >= 5;

        if (blinksPassed && headMovementPassed) {
            this.isActive = false;
            
            console.log('Liveness confirmed!', {
                blinks: this.blinkCount,
                headMovement: this.headPositions.length
            });
            
            if (this.callbacks.onLivenessConfirmed) {
                this.callbacks.onLivenessConfirmed({
                    blinks: this.blinkCount,
                    headMovement: this.calculateHeadMovement(),
                    duration: Date.now() - this.startTime
                });
            }
        }
    }

    /**
     * Set callback functions
     */
    on(event, callback) {
        if (this.callbacks.hasOwnProperty(`on${event.charAt(0).toUpperCase()}${event.slice(1)}`)) {
            this.callbacks[`on${event.charAt(0).toUpperCase()}${event.slice(1)}`] = callback;
        }
    }

    /**
     * Get current status
     */
    getStatus() {
        return {
            isActive: this.isActive,
            blinkCount: this.blinkCount,
            requiredBlinks: this.requiredBlinks,
            headMovement: this.calculateHeadMovement(),
            progress: this.calculateProgressPercentage(),
            timeElapsed: this.startTime ? Date.now() - this.startTime : 0
        };
    }
}

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = LivenessDetector;
}
