<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./assets/css/bootstrap.css">
    <title>Document</title>
</head>
<body>
    <div class="container">
        <table class="table">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">File name</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody>
                @php($i =1)
                @foreach ($data as $d)
                <tr>
                    <th scope="row">{{$data->firstItem() + $loop->index}}</th>
                    <td>{{$d->file_name}}</td>
                    <td><a class="btn btn-primary" href="./startimport/{{$d->id}}">Import</a></td>
                  </tr>

            @endforeach


            </tbody>
          </table>
          {{ $data->links() }}
    </div>

</body>
</html>
