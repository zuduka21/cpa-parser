
<div class="btn-group">
    <a href="{{route('admin_countries_edit',['id'=>$id])}}" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Edit country">
        <i class="fa fa-pencil"></i>
    </a>
    <a href="{{route('admin_countries_delete',['id'=>$id])}}" class="btn btn-sm btn-danger"  onclick="return confirm('Are you sure to delete?')" data-toggle="tooltip" title="DELETE COUNTRY">
        <i class="fa fa-times"></i>
    </a>
</div>
