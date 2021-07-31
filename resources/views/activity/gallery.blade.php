@if ($activity->getFirstMedia('activity'))
    
    <div class="card card-primary gallery">
        <div class="card-header">
            <div class="card-title">
                Galeria
            </div>
        </div>
        <div class="card-body">
            <div class="row">            
                @foreach ($activity->getMedia('activity') as $media)
                    <div class="col-sm-3 mt-2 files">
                        @if ($media->type == 'video')
                            <a href="{{ $media->getUrl() }}" data-fslightbox>
                                <img src="{{ asset('img/video-thumbnail-default.png') }}" class="img-fluid" />
                            </a>
                        @else									
                            <a href="{{ $media->getUrl() }}" data-fslightbox>
                                <img src="{{ $media->getUrl() }}" class="img-fluid" />
                            </a>
                        @endif
                        
                        <a href="#" class="btn btn-danger btn-sm delete-media mt-2" data-media-id="{{ $media->id }}" alt="Excluir">
                            <i class="fa fa-trash"></i>
                        </a>
                        <hr>
                    </div>                    
                @endforeach
            </div>
        </div>
    </div>
    
@endif