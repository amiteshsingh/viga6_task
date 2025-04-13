<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">My Dashboard</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="/dashboard">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="/profile">Profile</a></li>
                <li class="nav-item"><a class="nav-link active" href="/search">Search</a></li>
                <li class="nav-item"><a class="nav-link" href="/logout">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="card p-4">
        <h2 class="mb-4">Search Image/Video </h2>
        <form id="pixabaySearchForm" class="input-group mb-4">
            <input type="text" name="query" id="queryInput" class="form-control" placeholder="Search Pixabay" required>
            <button type="submit" class="btn btn-primary">Search</button>&nbsp;
            <button type="button" id="reset" class="btn btn-default">Reset</button>
        </form>

        <div id="searchResults" class="row mt-4"></div>

       
    </div>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $("#reset").click(function(){
        $('#queryInput').val('');
    })

    $('#pixabaySearchForm').on('submit', function(e) {
        e.preventDefault();
        let query = $('#queryInput').val();

        if (!query.trim()) return;

        $.ajax({
            url: '/ajax-search',
            method: 'POST',
            data: { query: query },
            success: function(response) {
                let html = '';
                if (response.length > 0) {
                    response.forEach(img => {
                        html += `
                            <div class="col-md-3 col-sm-4 col-6 mb-4">
                                <div class="card">
                                    <img src="${img.previewURL}" class="card-img-top" alt="Image">
                                    <div class="card-body">
                                        <p class="card-text text-muted small">${img.tags}</p>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    html = '<p>No results found.</p>';
                }
                $('#searchResults').html(html);
            },
            error: function() {
                $('#searchResults').html('<p class="text-danger">Error fetching results.</p>');
            }
        });
    });
</script>



</body>
</html>
