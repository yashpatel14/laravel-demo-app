<!DOCTYPE html>
<html>
<head>
    <title>Users List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">Users List</h2>
        <div class="row">
             <!-- Left Sidebar for Filters -->
             <div class="col-md-3">
                <h4>Filters</h4>
                <form method="GET" action="{{ url('/users') }}">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ request('name') }}">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" name="email" class="form-control" value="{{ request('email') }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ url('/users') }}" class="btn btn-secondary">Reset</a>
                </form>
            </div>

            @foreach($users as $user)
    <div class="col-md-4">
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">{{ $user[0]->name }}</h5>
                <p class="card-text">{{ $user[0]->email }}</p>
                <hr>
                <h6>Details:</h6>
                @foreach($user as $detail)
                    <p>{{ $detail->desc }}</p>
                    @if($detail->image)
                        <img src="{{ asset('storage/' . $detail->image) }}" class="img-fluid rounded">
                    @endif
                    <hr>
                @endforeach
                <a href="{{ url('/users/edit/' . $user[0]->id) }}" class="btn btn-primary btn-sm">Edit</a>
                <form action="{{ url('/users/delete/' . $user[0]->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </div>
        </div>
    </div>
@endforeach

        </div>
    </div>
</body>
</html>
