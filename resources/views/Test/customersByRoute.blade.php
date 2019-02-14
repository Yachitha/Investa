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
                            <h4>Customers</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <form class="form-control" action="/getCustomersByRoute" method="post">
                                        @csrf
                                        <div class="form-group">
                                            <label for="route_id">Route Id</label>
                                            <input type="number" id="route_id" name="route_id"/>
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
