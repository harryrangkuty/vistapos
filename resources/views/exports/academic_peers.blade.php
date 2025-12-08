<html>
    <body>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Front Degree</th>
                    <th>Behind Degree</th>
                    <th>Gender</th>
                    <th>Job Title</th>
                    <th>Department</th>
                    <th>Institution</th>
                    <th>Expertise Field</th>
                    <th>Institution Email</th>
                    <th>Personal Email</th>
                    <th>Phone</th>
                    <th>Photo</th>
                    <th>Year</th>
                    <th>Program Name</th>
                    <th>Faculty</th>
                    <th>Contact Person Name</th>
                    <th>Contact Person Phone</th>
                    <th>Contact Person Email</th>
                    <th>Country</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $peer)
                    <tr>
                        <td>{{ $peer->name }}</td>
                        <td>{{ $peer->front_degree }}</td>
                        <td>{{ $peer->behind_degree }}</td>
                        <td>
                            @if ($peer->gender == 'L')
                                Laki Laki
                            @elseif ($peer->gender == 'P')
                                Perempuan
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $peer->job_title }}</td>
                        <td>{{ $peer->department }}</td>
                        <td>{{ $peer->institution }}</td>
                        <td>{{ $peer->expertise_field }}</td>
                        <td>{{ $peer->institution_email }}</td>
                        <td>{{ $peer->personal_email }}</td>
                        <td>{{ $peer->phone }}</td>
                        <td>{{ $peer->photo }}</td>
                        <td>{{ $peer->year }}</td>
                        <td>{{ $peer->program_name }}</td>
                        <td>{{ $peer->unit ? $peer->unit->name : '-' }}</td>
                        <td>{{ $peer->contact_person_name }}</td>
                        <td>{{ $peer->contact_person_phone }}</td>
                        <td>{{ $peer->contact_person_email }}</td>
                        <td>{{ $peer->country_name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>