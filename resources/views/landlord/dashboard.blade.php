<!DOCTYPE html>
<html>
<head>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>

    <nav class="navbar navbar-light navbar-expand-lg mb-5" style="background-color: #e3f2fd;">
        <div class="container">
            <a class="navbar-brand mr-auto" href="{{ route('client.dashboard') }}">{{ __('Dashboard') }}</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse " id="navbarNav" >
                <ul class="navbar-nav" >
                    @auth('landlord')
                    <li class="nav-item ">
                        <a class="nav-link " href="{{ route('user.signout') }}" >Logout</a>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
    @yield('content')
    <div class="container">
        <h4>Hello, {{ auth()->guard('landlord')->user()->name }}</h4>
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        <button type="button" class="btn btn-outline-primary" id="create-btn" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
            Add Company
        </button>
        <br><br>
        <table class="table">
            <thead class="table-dark">
                <tr>
                    <td>Company Name</td>
                    <td>Domain</td>
                    <td>Database</td>
                    <td>Action</td>
                </tr>
            </thead>
            <tbody>
            @forelse ($tenantRecords as $tenantRecord)
                <tr>
                    <td>{{ $tenantRecord->name }}</td>
                    <td><a href="http://{{$tenantRecord->domain}}:8000" target="_blank">{{ $tenantRecord->domain }}</a></td>
                    <td>{{ $tenantRecord->database }}</td>
                    <td>
                        <button style="border:0;background:transparent;" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu" style="text-align:center;">
                            <li> 
                                <div class="row">
                                    <div class="col-xs-6" >
                                    <i class="far fa-edit edit-btn" data-id="{{$tenantRecord->id}}" style="cursor:pointer" data-bs-toggle="modal" data-bs-target="#staticBackdrop"></i>
                                    </div>
                                    <div class="col-xs-6">
                                        <form method="POST" action="{{ route('tenant.delete', $tenantRecord->id) }}">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" onclick="return confirm('Are you very sure?')" style="border:0;background:transparent;">
                                            <i class="far fa-trash-alt"></i>
                                        </form>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </td>
                </tr>
            @empty
                <p>No Company Found!</p>
            @endforelse
            </tbody>
        </table>
        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" 
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title brand" id="staticBackdropLabel">New Company</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">                    
                    <form action="{{ route('tenant.store') }}" method="post" id="create-form">
                    <div id="put"></div>
                    @csrf
                        <input type="hidden" value="" id="tnt-id" name="tnt-id">
                        <div class="mb-3">
                            <label for="name" class="form-label">Company Name</label>
                            <input type="text" class="form-control" id="name" :value="old('name')" name="name" placeholder="abc" 
                            required autofocus autocomplete="name">
                        </div>
                        <div class="mb-3">
                            <label for="domain" class="form-label">Domain</label>
                            <input type="text" class="form-control" id="domain" :value="old('domain')" name="domain" placeholder="xyx" 
                            required autofocus autocomplete="domain">
                            <span id="preview-domain"></span>
                        </div>
                        <div class="mb-3">
                            <label for="database" class="form-label">Database</label>
                            <input type="text" class="form-control" id="database" :value="old('database')" name="database" placeholder="db" 
                            required autofocus autocomplete="database">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-outline-success">Save</button>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
        </div>
        
    </div>
    <script>
        var domain = document.getElementById("domain");

        domain.addEventListener('change', function(){
            if(domain.value != ""){
                document.getElementById("preview-domain").innerHTML = domain.value+".localhost";
            }else{
                document.getElementById("preview-domain").innerHTML = "";
            }
        });
    </script>
    <script>
        $(function(){
            $('.edit-btn').click(function(){
                var tntId = $(this).data('id');
                $.ajax({
                    url: "{{ url('/edit/tenant') }}",
                    method: 'get',
                    data: {tntId: tntId},
                    success: function(result){
                        $('#name').val(result['name']);
                        $('#domain').val(result['domain']);
                        $('#database').val(result['database']);
                        $('#tnt-id').val(result['id']);
                        $('#create-form').attr("action", window.location.origin+"/update/tenant/"+result['id']);
                        $('.brand').text('Update '+result['name']+' Company');
                    }
                });    
                $('#domain').attr('disabled','disabled');
                $('#database').attr('disabled','disabled');  
                $('#put').html('<input type="hidden" name="_method" value="PUT">');   
            });

            $('#create-btn').click(function(){
                if($('#tnt-id').val()){
                    $('.brand').text('New Company');
                    $('#create-form').attr("action", window.location.origin+"/post/company");
                    $("#put").html("");
                    $('#tnt-id').val("");
                    $("form").each(function(){
                        $(this).find(':input[type="text"]').val('');
                        $(this).find(':input').attr('disabled',false);
                    });
                }
                
            });            
        })
    </script>
</body>

</html>