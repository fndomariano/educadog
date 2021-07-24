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
                            <a href="{{ $media->getUrl() }}" target="_blank">
                                <video class="img-fluid mb-2">
                                    <source src="{{ $media->getUrl() }}" type="video/mp4">]
                                    Your browser does not support the video tag.
                                </video>
                            </a>
                        @else									
                            <a href="{{ $media->getUrl() }}" data-toggle="lightbox" data-title="Imagem" data-gallery="gallery">
                                <img src="{{ $media->getUrl() }}" class="img-fluid mb-2" alt="white sample" />	
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    
@endif