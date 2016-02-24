@extends('layouts.protected')
@section('content')
<style>
    .tooly + .tooltip > .tooltip-inner {
        background-color: #ffff64;
        color: #000;
        max-width: 305px;
    }
    .tooly + .tooltip > .tooltip-arrow{
        margin-top:-5px;
        border-top:5px solid  #ffb2b2;
        border-right:5px solid transparent;
        border-left:5px solid transparent;
    }
    .add-people-box {
        font-family: 'Open Sans',sans-serif;
    }
   .add-people-box .heapBox .heap{
    width: 170px;
 }
 .add-people-box .heapBox .heap .heapOptions .heapOption a{
    font-size:14px;
    text-indent:10px;
}
.add-people-box .heapBox .heap {
    width: 129px;
}
 .add-people-box .heapBox .handler{
    width: 20%;
    z-index: 1;
}
.add-people-box .heapBox{
    width: 130px;
}
.add-people-box .heapBox .holder{
    width: 100px;
    font-size: 14px;
    text-indent: 10px;
    z-index: 1;
}
.rounded-corner{border-radius:11px!important; width:85%!important;}
.txt-time{ float:left; width:45%!important; margin-right:5px;}
.row { padding-bottom:10px; margin-bottom:10px;}
.txt-background { background-color: #f1f1f1;
   border-style: solid;
    border-width: 1px;
    color: #000;
    font-family: Arial,Helvetica,sans-serif;
    font-size: 15px;
    padding: 10px;}
.addbtn { padding:4px;border-width: 1px;color:#fff; background-color:#6DB9EA;border-radius:12px!important;} 
</style>


<div class="page container">    
    <div class="group-wrapper">        
        <div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 page-content">            
            <div class="contant-group">
              
                <div class="floating-group-form">
                 <h2 style="color:#3c4854; text-align:left;">Add New Group</h2>
                    {{ Form::open(array('url' => '/create-project','files' => true, 'id'=>'edit-project-form')) }}                          
                    <div class="form-group">
                    <div class="row">  
                    <div class="col-lg-6">                          
                        <label for="first-name">Group/Client  Name</label>  
                      <input type="text" name="title" placeholder="Group Name" class="rounded-corner" /> 
                       </div>
                        <div class="col-lg-6">                          
                        <label for="first-name">Company Site</label>  
                      <input type="text" name="title" placeholder="https://www.youtube.com/" class="rounded-corner" /> 
                       </div> 
                      </div> 
                      
                      <div class="row">  
                    <div class="col-lg-6">                          
                      <label for="first-name">Note</label>  <textarea  class="rounded-corner txt-background" name="background" cols="20" rows="5" placeholder="Project admin can add description here"></textarea> 
                       </div>
                        <div class="col-lg-6"> 
                        <div class="row">
                         <div class="col-lg-12">                          
                        <label for="first-name">Company Youtube Page</label>  
                      <input type="text" name="title" placeholder="https://www.youtube.com/username" class="rounded-corner" /> 
                      </div>
                      </div>
                      <div class="row">
                       <div class="col-lg-12">                          
                         <i class="fa fa-plus-circle addbtn"> </i>
                        <label for="first-name">Add</label>  
                       </div>  
                      </div>
                       </div>
                      </div> 
                       
                    </div> 
                    <div class="seprator"></div>
                     <h2 style="color:#3c4854">Add People</h2>
                     <div class="form-group">
                    <div class="row">  
                    <div class="col-lg-6">                          
                       <label for="first-name" style="visibility:hidden"> test </label>
                      <input type="text" name="email" placeholder="Email Address" class="rounded-corner" /> 
                       </div> 
                      <div class="col-lg-6">                          
                        <label for="first-name">Link to publishing project</label>  
                      <input type="text" name="project1" placeholder="Project A" class="rounded-corner" /> 
 
                       </div>
                         
                      </div> 
                      
                      
                    </div>
                    <div class="clearfix"></div>
                    
                    
                    <div class="seprator"></div>
                    
                    {{ Form::close() }} 
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection