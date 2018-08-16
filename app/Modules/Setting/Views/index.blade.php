@extends('master')

@section('css')
<link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/select2.min.css') }}">
@endsection
@section('js')
<script src="{{ asset('adminlte/plugins/select2/select2.full.min.js') }}"></script>

@endsection

@section('content')
<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-12">
      @include('layouts.errors')
      <div class="card">
        
        <div class="card-header" style="border-bottom: 0">
          <h3 class="card-title">Settings</h3>
        </div>
        
        <div class="card-body p-0">
          <form action="" class="" method="POST" enctype="multipart/form-data">
            <div class="col-md-12" style="display: inline-block;border-top: 1px solid #ccc;height: auto;">
              
              <div class="col-md-4 float-left text-center" style="display: inline-block">
                  <div class="form-group">
                    <input type="hidden" name="image" id="image" />
                    <Button onclick="selectFileWithCKFinder('image')">Choose image</Button>
                    <input class="form-control" placeholder="Enter favicon" name="favicon" type="hidden" value="{{ $setting['favicon'] or old('favicon') }}">
                    <a class="btn btn-default btn_upload_image @if( $setting['favicon'] )  hide @endif" file_type="image" selecter="favicon">Upload <i class="fa fa-cloud-upload"></i></a>
                    <div class="uploaded_image @if( !$setting['favicon'] )  hide @endif">
                      <img src="{{ $setting['favicon_link'] }}"><i title="Remove Image" class="fa fa-times"></i>
                    </div>
                    
                  </div>
                  <label style="display:block">Favicon Icon</label>
              </div>

              <div class="col-md-4 float-left text-center" style="display: inline-block">
                <div class="form-group">
                    <input class="form-control" placeholder="Enter logo" name="logo" type="hidden" value="{{ $setting['logo'] or old('logo') }}">
                    <a class="btn btn-default btn_upload_image @if( $setting['logo'] )  hide @endif" file_type="image" selecter="logo">Upload <i class="fa fa-cloud-upload"></i></a>
                    <div class="uploaded_image @if( !$setting['logo'] )  hide @endif">
                      <img src="{{ $setting['logo_link'] }}"><i title="Remove Image" class="fa fa-times"></i>
                    </div>
                    
                  </div>
                  <label style="display:block">Website Logo</label>
              </div>
              <div class="col-md-4 float-left text-center" style="display: inline-block">
                <div class="form-group">
                    <input class="form-control" placeholder="Enter backendlogo" name="backendlogo" type="hidden" value="{{ $setting['favicon'] or old('favicon') }}">
                    <a class="btn btn-default btn_upload_image @if( $setting['backendlogo'] )  hide @endif" file_type="image" selecter="backendlogo">Upload <i class="fa fa-cloud-upload"></i></a>
                    <div class="uploaded_image @if( !$setting['backendlogo'] )  hide @endif">
                      <img src="{{ $setting['backendlogo_link'] }}"><i title="Remove Image" class="fa fa-times"></i>
                    </div>
                    
                  </div>
                  <label style="display:block">Backend Logo</label>
              </div>
            
                    
            </div>
            <div class="col-md-6" style="display: inline-block; float: left">
              <table class="table table-striped">
              <tbody>


              
                          
              <tr>
                <td>Website name</td>
                <td>
                  <div class="input-group">
                    <input type="text" name="name" class="form-control" value="{{ $setting['name'] or old('name') }}">
                  </div>
                </td>
              </tr>

              <tr>
                <td>Website title</td>
                <td>
                  <div class="input-group">
                    <input type="text" name="title" class="form-control" value="{{ $setting['title'] or old('title') }}">
                  </div>
                </td>
              </tr>

              <tr>
                <td>Web Description</td>
                <td>
                  <div class="input-group">
                    <input type="text" name="description" class="form-control" value="{{ $setting['description'] or old('description') }}">
                  </div>
                </td>
              </tr>
              <tr>
                <td>Web Language:</td>
                <td>
                  <div class="form-group">
                    {!! Form::select('language', $languages,$setting['language'], array('class' => 'form-control')) !!}
                  </div>
                </td>
                
              </tr>
              <tr>
                <td>Email Address</td>
                <td>
                  <div class="input-group">
                    <input type="email" name="email" class="form-control" value="{{ $setting['email'] or old('email') }}">
                  </div>
                </td>
                
              </tr>
              <tr>
                <td>Phone number</td>
                <td>
                  <div class="input-group">
                    <input type="text" name="phone" class="form-control" value="{{ $setting['phone'] or old('phone') }}">
                  </div>
                </td>
                
              </tr>

              <tr>
                <td>Hotline</td>
                <td>
                  <div class="input-group">
                    <input type="text" name="hotline" class="form-control" value="{{ $setting['hotline'] or old('hotline') }}">
                  </div>
                </td>
                
              </tr>

              
                <tr>
                <td>Facebook Address</td>
                <td>
                  <div class="input-group">
                    <input type="text" name="facebook" class="form-control" value="{{ $setting['facebook'] or old('facebook') }}">
                  </div>
                </td>
                
              </tr>

              <tr>
                <td>Twitter Address</td>
                <td>
                  <div class="input-group">
                    <input type="text" name="twitter" class="form-control" value="{{ $setting['twitter'] or old('twitter') }}">
                  </div>
                </td>
                
              </tr>

              <tr>
                <td>Google plus Address</td>
                <td>
                  <div class="input-group">
                    <input type="text" name="googleplus" class="form-control" value="{{ $setting['googleplus'] or old('googleplus') }}">
                  </div>
                </td>
                
              </tr>

              

            </tbody></table></div>


            <div class="col-md-6" style="display: inline-block;">
            <table class="table table-striped ">
              <tbody>
                <tr>
                  <td>Backend name:</td>
                  <td>
                    <div class="input-group">
                      <input type="text" name="backendname" class="form-control" value="{{ $setting['backendname'] or old('backendname') }}">
                    </div>
                  </td>
                </tr>
                <tr>
                <td>Admin Lang:</td>
                <td>
                  <div class="form-group">
                    {!! Form::select('backendlang', $languages,$setting['backendlang'], array('class' => 'form-control')) !!}
                  </div>
                </td>
                
              </tr>

                

              <tr>
                <td>Copyright text</td>
                <td>
                  <div class="input-group">
                    <input type="text" name="copyright" class="form-control" value="{{ $setting['copyright'] or old('copyright') }}">
                  </div>
                </td>
                
              </tr>

              
              <tr>
                <td>Default Timezone</td>
                <td>
                  <div class="form-group">
                    {!! Form::select('timezone', $timezone,$setting['timezone'], array('class' => 'form-control select2')) !!}
                  </div>
                </td>
                
              </tr>
              <tr>
                <td>Website Status:</td>
                <td>
                  <div class="form-group">
                    <select class="form-control" name="websitestatus" id="websitestatus">
                      <option value="ONLINE" @if($setting['websitestatus'] == 'ONLINE') selected="selected" @endif >ONLINE</option>
                      <option value="OFFLINE" @if($setting['websitestatus'] == 'OFFLINE') selected="selected" @endif>OFFLINE</option>
                    </select>
                  </div>
                </td>
                
              </tr>
              <tr>
                
                <td>
                  <div class="input-group">
                    <button class="btn btn-success">Submit</button>
                  </div>
                </td>
                <td></td>
                
              </tr>
              </tbody>
            </table>

          </div>
          {!! csrf_field() !!}
          </form>
          </div>
        <!-- /.card-body -->
      </div>
<!-- /.card -->
</div>
  <!-- /.col -->
</div>
<!-- /.row -->
</section>
<!-- /.content -->
@endsection