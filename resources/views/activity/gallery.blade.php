@if ($activity->getFirstMedia('activity'))
    
    <div class="card card-primary">
        <div class="card-header">
            <div class="card-title">
                Galeria
            </div>
        </div>
        <div class="card-body">
            <div class="row">            
                @foreach ($activity->getMedia('activity') as $media)
                    <div class="col-sm-3">
                        @if ($media->type == 'video')
                            <a href="#" id="modal-video-{{ $media->id }}" class="modal-video">
                                <video class="img-fluid embed-responsive-item modal-video-{{ $media->id }}">
                                    <source src="{{ $media->getUrl() }}" type="video/mp4">]
                                    Your browser does not support the video tag.
                                </video>
                            </a>
                        @else									
                            <a href="#" class="modal-img">
                                <img src="{{ $media->getUrl() }}" class="mw-50 img-fluid mb-2 modal-img-{{ $media->id }}" />
                            </a>
                        @endif

                        <a href="#" class="btn btn-danger btn-sm delete form-delete-{{ $media->id }}" alt="Excluir">
                            <i class="fa fa-trash"></i>
                        </a>
                        
                        <form action="{{ route('activity_delete_media', $media->id) }}" method="post" class="form-delete-{{ $media->id }}" style="display: inline;">
                            @csrf
                            <input type="hidden" name="_method" value="DELETE">                                    
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    
@endif