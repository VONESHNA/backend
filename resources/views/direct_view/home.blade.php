<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>
<body>
    <h1>Registered Users</h1>
    <table>
        <tr>
            <th>Photo</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        @foreach($users as $user)
        <tr>
            <td>
            @if($user->photo)
                    <img src="{{ asset('storage/' . $user->photo) }}" alt="User Photo" style="width: 50px; height: 50px; border-radius: 50%;">
                @else
                    <img src="{{ asset('storage/uploads/image.png') }}" alt="Default Photo" style="width: 50px; height: 50px; border-radius: 50%;">
                @endif
            </td>
            <td>{{ $user->first_name }}</td>
            <td>{{ $user->last_name }}</td>
            <td>{{ $user->email }}</td>
            <td>
                <form action="{{ route('direct.user.delete', $user->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
                <a href="{{ route('direct.user.edit', $user->id) }}">Edit</a>
            </td>
        </tr>
        @endforeach
    </table>
    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>

    <form id="logout-form" action="{{ route('direct.logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</body>
</html>
