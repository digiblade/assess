<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{url('')}}/assets/css/bootstrap.css">
   <title>Document</title>
</head>
<body>

    <div class="container">
        <table class="table">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Admno</th>
                <th scope="col">Roll no</th>
                <th scope="col">Receipt no</th>
                <th scope="col">Academic year</th>
                <th scope="col">Due Amount</th>
                <th scope="col">Paid Amount</th>
                <th scope="col">Consession Amount</th>
                <th scope="col">Scholarship Amount</th>
                <th scope="col">Reverse Consession Amount</th>
                <th scope="col">Write Off Amount</th>
                <th scope="col">Adjusted Amount</th>
                <th scope="col">Refund  Amount</th>
                <th scope="col">Fund Transfer Amount</th>

              </tr>
            </thead>
            <tbody>
                @php($i =1)
                @foreach ($data as $d)
                <tr>
                    <th scope="row">{{$data->firstItem() + $loop->index}}</th>
                    <td>{{$d->detail_admno}}</td>
                    <td>{{$d->getHead->trans_rollno}}</td>
                    <td>{{$d->getHead->trans_rollno}}</td>
                    <td>{{$d->getHead->trans_session}}</td>
                    <td>{{$d->detail_due_amount}}</td>
                    <td>{{$d->detail_paid_amount}}</td>
                    <td>{{$d->detail_concession_amount}}</td>
                    <td>{{$d->detail_scholarship_amount}}</td>
                    <td>{{$d->detail_reverse_concession_amount}}</td>
                    <td>{{$d->detail_write_offamount}}</td>
                    <td>{{$d->detail_adjusted_amount}}</td>
                    <td>{{$d->detail_refund_amount}}</td>
                    <td>{{$d->detail_fund_tranCfer_amount}}</td>

                  </tr>

            @endforeach


            </tbody>
          </table>
          {{ $data->links() }}
    </div>


</body>
</html>

