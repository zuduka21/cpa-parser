
<div class="btn-group">
    <a href="{{route('admin_users_edit',['id'=>$id])}}" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Edit user">
        <i class="fa fa-pencil"></i>
    </a>
    <a href="{{route('admin_users_delete',['id'=>$id])}}" class="btn btn-sm btn-danger"  onclick="return confirm('Are you sure to delete?')" data-toggle="tooltip" title="DELETE USER">
        <i class="fa fa-times"></i>
    </a>
</div>
