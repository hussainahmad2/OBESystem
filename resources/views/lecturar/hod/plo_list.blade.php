@extends('lecturar.dashboard')

@section('title', 'Program Learning Outcomes (PLOs)')

@section('content')
<div class="container mt-4">
    <div class="topbar" style="display: flex; align-items: center; justify-content:space-between; margin-bottom: 10px;">
        <h2 class="heading" style="margin:0; color:#23546B; font-weight:700;">Program Learning Outcomes (PLOs)</h2>
        <a href="{{ route('duty.dashboard', ['duty' => 'HOD']) }}" class="btn innerbuttons" style="background: #317c8c; color: white; border: none;">View Courses</a>
    </div>
    <table class="faculty-table" style="width: 100%; margin-top: 20px; background-color: #ffffff; border-collapse: collapse; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
        <thead>
            <tr>
                <th style="background-color: #23546B; color: #ffffff; font-weight: bold;">PLO #</th>
                <th style="background-color: #23546B; color: #ffffff; font-weight: bold;">Title</th>
                <th style="background-color: #23546B; color: #ffffff; font-weight: bold;">Description</th>
                <th style="background-color: #23546B; color: #ffffff; font-weight: bold;">Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Engineering Knowledge</td>
                <td>An ability to apply knowledge of mathematics, science, engineering fundamentals, and an engineering specialization to the solution of complex engineering problems.</td>
                <td><a href="{{ route('hod.plo.edit', ['number' => 1]) }}" class="btn btn-outline-info btn-sm">Edit</a></td>
            </tr>
            <tr>
                <td>2</td>
                <td>Problem Analysis</td>
                <td>An ability to identify, formulate, research literature, and analyze complex engineering problems reaching substantiated conclusions using first principles of mathematics, natural sciences, and engineering sciences.</td>
                <td><a href="{{ route('hod.plo.edit', ['number' => 2]) }}" class="btn btn-outline-info btn-sm">Edit</a></td>
            </tr>
            <tr>
                <td>3</td>
                <td>Design/Development of Solutions</td>
                <td>An ability to design solutions for complex mechanical engineering problems and design systems, components, or processes that meet specified needs with appropriate consideration for public health and safety, cultural, societal, and environmental considerations.</td>
                <td><a href="{{ route('hod.plo.edit', ['number' => 3]) }}" class="btn btn-outline-info btn-sm">Edit</a></td>
            </tr>
            <tr>
                <td>4</td>
                <td>Investigation</td>
                <td>An ability to investigate complex engineering problems in a methodical way including literature survey, design and conduct of experiments, analysis and interpretation of experimental data, and synthesis of information to derive valid conclusions.</td>
                <td><a href="{{ route('hod.plo.edit', ['number' => 4]) }}" class="btn btn-outline-info btn-sm">Edit</a></td>
            </tr>
            <tr>
                <td>5</td>
                <td>Modern Tool Usage</td>
                <td>An ability to create, select, and apply appropriate techniques, resources, and modern engineering and IT tools, including prediction and modeling, to complex engineering activities, with an understanding of the limitations.</td>
                <td><a href="{{ route('hod.plo.edit', ['number' => 5]) }}" class="btn btn-outline-info btn-sm">Edit</a></td>
            </tr>
            <tr>
                <td>6</td>
                <td>The Engineer and Society</td>
                <td>An ability to apply reasoning informed by contextual knowledge to assess societal, health, safety, legal, and cultural issues and the consequent responsibilities relevant to professional engineering practice and solutions to complex engineering problems.</td>
                <td><a href="{{ route('hod.plo.edit', ['number' => 6]) }}" class="btn btn-outline-info btn-sm">Edit</a></td>
            </tr>
            <tr>
                <td>7</td>
                <td>Environment and Sustainability</td>
                <td>An ability to understand the impact of professional engineering solutions in societal and environmental contexts and demonstrate knowledge of, and need for sustainable development.</td>
                <td><a href="{{ route('hod.plo.edit', ['number' => 7]) }}" class="btn btn-outline-info btn-sm">Edit</a></td>
            </tr>
            <tr>
                <td>8</td>
                <td>Ethics</td>
                <td>Apply ethical principles and commit to professional ethics and responsibilities and norms of engineering practice.</td>
                <td><a href="{{ route('hod.plo.edit', ['number' => 8]) }}" class="btn btn-outline-info btn-sm">Edit</a></td>
            </tr>
            <tr>
                <td>9</td>
                <td>Individual and Teamwork</td>
                <td>An ability to work effectively, as an individual or in a team, on multifaceted and /or multidisciplinary settings.</td>
                <td><a href="{{ route('hod.plo.edit', ['number' => 9]) }}" class="btn btn-outline-info btn-sm">Edit</a></td>
            </tr>
            <tr>
                <td>10</td>
                <td>Communication</td>
                <td>An ability to communicate effectively, orally as well as in writing, on complex engineering activities with the engineering community and with society at large, such as being able to comprehend and write effective reports and design documentation, make effective presentations, and give and receive clear instructions.</td>
                <td><a href="{{ route('hod.plo.edit', ['number' => 10]) }}" class="btn btn-outline-info btn-sm">Edit</a></td>
            </tr>
            <tr>
                <td>11</td>
                <td>Project Management</td>
                <td>An ability to demonstrate management skills and apply engineering principles to one's own work, as a member and/or leader in a team, to manage projects in a multidisciplinary environment.</td>
                <td><a href="{{ route('hod.plo.edit', ['number' => 11]) }}" class="btn btn-outline-info btn-sm">Edit</a></td>
            </tr>
            <tr>
                <td>12</td>
                <td>Lifelong Learning</td>
                <td>An ability to recognize the need for, and have the preparation and ability to engage in, independent and life-long learning in the broadest context of technological change</td>
                <td><a href="{{ route('hod.plo.edit', ['number' => 12]) }}" class="btn btn-outline-info btn-sm">Edit</a></td>
            </tr>
        </tbody>
    </table>
</div>
@endsection 