<!DOCTYPE html>
<html>
<head>
    <title>Add Users</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <form action="{{ url('/users/store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div id="user-wrapper">
            <div class="user-group">
                <input type="text" name="users[0][name]" placeholder="Name" required>
                <input type="email" name="users[0][email]" placeholder="Email" required>
                <input type="password" name="users[0][password]" placeholder="Password" required>
                <button type="button" class="add-detail">Add Detail</button>
                <div class="detail-wrapper"></div>
            </div>
        </div>
        <button type="button" id="add-user">Add More User</button>
        <button type="submit">Submit</button>
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
                    <div class="detail-wrapper"></div>
                </div>
            `);
            userIndex++;
        });

        $(document).on('click', '.add-detail', function () {
            let parent = $(this).closest('.user-group');
            let detailIndex = parent.find('.detail-wrapper .detail-group').length;
            console.log(parent);
            console.log(detailIndex);
            parent.find('.detail-wrapper').append(`
                <div class="detail-group">
                    <input type="text" name="users[${userIndex - 1}][details][${detailIndex}][desc]" placeholder="Description" required>
                    <input type="file" name="users[${userIndex - 1}][details][${detailIndex}][image]">
                </div>
            `);
        });
    </script>
</body>
</html>
