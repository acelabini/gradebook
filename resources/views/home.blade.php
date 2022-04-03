<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Grade Book</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
        .invalid-feedback {
            display: block;
        }
    </style>
</head>
<body>
<div class="container pt-5">
    <div class="row pt-5">
        <div class="col">
            <form method="post" action="{{ route('post') }}" enctype="multipart/form-data">
                @csrf
                @if ($errors->has('grades'))
                    <div class="invalid-feedback">
                        <strong>{{ $errors->first('grades') }}</strong>
                    </div>
                @endif
                <div class="mb-3">
                    <label class="form-label">Input grades</label>
                    <textarea class="form-control{{ $errors->has('grades') ? ' is-invalid' : '' }}" name="grades" rows="10"></textarea>
                </div>
                <div>OR</div>
                @if ($errors->has('grades_file'))
                    <div class="invalid-feedback">
                        <strong>{{ $errors->first('grades_file') }}</strong>
                    </div>
                @endif
                <div class="mt-3">
                    <label class="form-label">Upload grades</label>
                    <div class="input-group mb-3">
                        <input type="file" class="form-control{{ $errors->has('grades_file') ? ' is-invalid' : '' }}" id="upload" name="grades_file" accept="text/plain">
                        <label class="input-group-text" for="upload">Upload</label>
                    </div>
                </div>
                <div class="mt-3">
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
            </form>
        </div>
        <div class="col">
            <h3>Students</h3>
            <table class="table table-borderless">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Average</th>
                    </tr>
                </thead>
                @php $year = null; $quarter = null; @endphp
                @foreach ($students as $key => $student)
                    @if ($year != $student->year || $quarter != $student->quarter)
                        <thead>
                        <tr>
                            <th scope="col" colspan="3">
                                Quarter {{ $student->quarter }}, {{ $student->year }}
                            </th>
                        </tr>
                        </thead>
                        @php $year = $student->year; $quarter = $student->quarter; @endphp
                    @endif
                    <tbody>
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $student->student->name }}</td>
                            <td>{{ $student->average }}</td>
                        </tr>
                    </tbody>
                @endforeach
            </table>
        </div>
    </div>
</div>
</body>
</html>
