<!DOCTYPE html>
<html>
<head>
    <title>Academic Transcript</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .student-info { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .passed { color: green; }
        .failed { color: red; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Academic Transcript</h1>
        <h2>{{ config('app.name') }}</h2>
    </div>

    <div class="student-info">
        <p><strong>Student Name:</strong> {{ $student->name }}</p>
        <p><strong>Email:</strong> {{ $student->email }}</p>
        <p><strong>Generated on:</strong> {{ now()->format('M d, Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Quiz Title</th>
                <th>Score</th>
                <th>Percentage</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $result)
            <tr>
                <td>{{ $result->quiz->title }}</td>
                <td>{{ $result->score }}/{{ $result->quiz->questions->count() }}</td>
                <td>{{ number_format(($result->score / $result->quiz->questions->count()) * 100, 1) }}%</td>
                <td class="{{ $result->passed ? 'passed' : 'failed' }}">
                    {{ $result->passed ? 'Passed' : 'Failed' }}
                </td>
                <td>{{ $result->completed_at->format('M d, Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
