<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
</head>
<body>
    <h1>Edit User</h1>
    <form action="{{ route('direct.user.update', $user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="text" name="first_name" value="{{ $user->first_name }}" required>
        <input type="text" name="last_name" value="{{ $user->last_name }}" required>
        <input type="date" name="dob" value="{{ $user->dob }}" required>
        <input type="text" name="address" value="{{ $user->address }}" required>
        <input type="email" name="email" value="{{ $user->email }}" required>
        <input type="text" name="phone" value="{{ $user->phone }}" required>
        <input type="file" name="photo">
        <button type="submit">Update</button>
    </form>
    <a href="{{ route('direct.home') }}">Back to Home</a>
</body>
</html>
