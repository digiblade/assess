<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{url('')}}/assets/css/bootstrap.css">
    <title>Convert</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-8">
                <div class="card">
                    <div class="card-body">
                        <form action="{{url('/dummy/submit')}}" class="m-2" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="exampleInputEmail1">Email address</label>
                                <input type="file"   name="file" class="form-control mt-2" id="exampleInputEmail1" />
                              </div>
                              <input type="submit" class="btn btn-primary mt-4" name="submit" value="Submit">
                        </form>
                    </div>

                </div>

            </div>
        </div>
    </div>

</body>
</html>
