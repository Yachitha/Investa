<!Doctype html>
<html lang="en">
<head>
    <title>Login Southern Property Developers</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-primary">
                    <h4>CashBook</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <form class="form-control" action="/getCashBook" method="post">
                                @csrf
                                <div class="form-group">
                                    <label for="month">Month</label>
                                    <input type="month" id="month" name="month"/>
                                </div>
                                <div class="form-group">
                                    <input type="submit"/>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
