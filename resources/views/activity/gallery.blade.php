@if ($activity['media']->count() > 0)
    
    <div class="card card-primary gallery">
        <div class="card-header">
            <div class="card-title">
                Galeria
            </div>
        </div>
        <div class="card-body">
            <div class="row">            
                @foreach ($activity['media'] as $media)
                    <div class="col-sm-3 mt-2 files">
                        @if ($media['type'] == 'video')
                            <a href="{{ $media['url'] }}" data-fslightbox>
                                <img src="{{ asset('img/video-thumbnail-default.png') }}" class="img-fluid" />
                            </a>
                        @else									
                            <a href="{{ $media['url'] }}" data-fslightbox>
                                <img src="{{ $media['url'] }}" class="img-fluid" />
                            </a>
                        @endif
                        
                        <a href="#" class="btn btn-danger btn-sm delete-media mt-2" data-media-id="{{ $media['id'] }}" alt="Excluir">
                            <i class="fa fa-trash"></i>
                        </a>
                        <hr>
                    </div>                    
                @endforeach
            </div>
        </div>
    </div>
    
@endif