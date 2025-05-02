<!DOCTYPE html>
<html>
<head>
    <title>Add Users</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .user-group, .detail-group { margin-bottom: 15px; border: 1px solid #ccc; padding: 10px; }
        .remove-btn { margin-left: 10px; color: red; cursor: pointer; }
    </style>
</head>
<body>
    <form action="{{ url('/users/update/' . $user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="text" name="name" value="{{ $user->name }}" required>
        <input type="email" name="email" value="{{ $user->email }}" required>
        <input type="password" name="password" placeholder="New Password (optional)">

        <div id="detail-wrapper">
            @foreach ($details as $i => $detail)
                <div class="detail-group">
                    <input type="text" name="details[{{ $i }}][desc]" value="{{ $detail->desc }}" required>
                    <input type="file" name="details[{{ $i }}][image]">
                    @if ($detail->image)
                        <img src="{{ asset('storage/' . $detail->image) }}" width="100">
                    @endif
                </div>
            @endforeach
        </div>

        <button type="submit">Update User</button>
    </form>



    <script>
        let userIndex = 1;

        $('#add-user').click(function () {
            $('#user-wrapper').append(`
                <div class="user-group">
                    <input type="text" name="users[${userIndex}][name]" placeholder="Name" required>
                    <input type="email" name="users[${userIndex}][email]" placeholder="Email" required>
                    <input type="password" name="users[${userIndex}][password]" placeholder="Password" required>
                    <button type="button" class="add-detail">Add Detail</button>
                    <span class="remove-user remove-btn">Remove User</span>
                    <div class="detail-wrapper"></div>
                </div>
            `);
            userIndex++;
        });

        $(document).on('click', '.add-detail', function () {
            let parent = $(this).closest('.user-group');
            let detailWrapper = parent.find('.detail-wrapper');
            let userIdx = parent.index(); // get user index dynamically
            let detailIndex = detailWrapper.find('.detail-group').length;

            detailWrapper.append(`
                <div class="detail-group">
                    <input type="text" name="users[${userIdx}][details][${detailIndex}][desc]" placeholder="Description" required>
                    <input type="file" name="users[${userIdx}][details][${detailIndex}][image]">
                    <span class="remove-detail remove-btn">Remove Detail</span>
                </div>
            `);
        });

        // Remove user
        $(document).on('click', '.remove-user', function () {
            $(this).closest('.user-group').remove();
        });

        // Remove detail
        $(document).on('click', '.remove-detail', function () {
            $(this).closest('.detail-group').remove();
        });
    </script>
</body>
</html>
