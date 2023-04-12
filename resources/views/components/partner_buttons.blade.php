
<div class="btn-group">
    <a href="{{route('admin_partners_edit',['indication'=>$indication])}}" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Edit partner">
        <i class="fa fa-pencil"></i>
    </a>
    <a href="{{route('admin_partners_delete',['id'=>$id])}}" class="btn btn-sm btn-danger"  onclick="return confirm('Are you sure to delete?')" data-toggle="tooltip" title="DELETE PARTNER">
        <i class="fa fa-times"></i>
    </a>
</div>
