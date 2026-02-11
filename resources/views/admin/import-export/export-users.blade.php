<table>
  <thead>
    <tr>
      <th>#</th>
      <th>NISN</th>
      <th>Name</th>
      <th>Email</th>
      <th>Phone</th>
      <th>Gender</th>
      <th>Birth Date</th>
      <th>Birth Place</th>
      <th>Address</th>
      <th>City</th>
      <th>Education</th>
      <th>Division</th>
      <th>Job Title</th>
      <th>Created At</th>
      <th>Updated At</th>
      <th>Password</th>
      <th>ID</th>
    </tr>
  </thead>
  <tbody>
    @if (isset($isTemplate) && $isTemplate)
      <tr>
        <td>1</td>
        <td>1234567890</td>
        <td>John Doe</td>
        <td>john@example.com</td>
        <td>08123456789</td>
        <td>L</td>
        <td>1990-01-01</td>
        <td>Jakarta</td>
        <td>Jl. Jend. Sudirman</td>
        <td>Jakarta</td>
        <td>S1</td>
        <td>IT</td>
        <td>Developer</td>
        <td>{{ now() }}</td>
        <td>{{ now() }}</td>
        <td>password123</td>
        <td>1</td>
      </tr>
    @endif
    @foreach ($users as $user)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td data-type="s">{{ $user->nip }}</td>
        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
        <td data-type="s">{{ $user->phone }}</td>
        <td>{{ $user->gender }}</td>
        <td>{{ $user->birth_date?->format('Y-m-d') }}</td>
        <td>{{ $user->birth_place }}</td>
        <td>{{ $user->address }}</td>
        <td>{{ $user->city }}</td>
        <td>{{ $user->education?->name }}</td>
        <td>{{ $user->division?->name }}</td>
        <td>{{ $user->jobTitle?->name }}</td>
        <td>{{ $user->created_at }}</td>
        <td>{{ $user->updated_at }}</td>
        <td>{{ $user->raw_password }}</td>
        <td>{{ $user->id }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
