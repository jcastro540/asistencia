$(document).ready(function () {

    const ias = jQuery.ias({
        container: '#timeline .box-content',
        item: '.publication-item',
        pagination: '#timeline .pagination',
        next: '.pagination .next_link',
        triggetPageThreshold: 5
    });

    ias.extension(new IASTriggerExtension({
        text: 'Ver más cursos',
        offset: 3
    }));

    ias.extension(new IASSpinnerExtension({
        src: URL + '/assets/images/loader.gif'
    }));

    ias.extension(new IASNoneLeftExtension({
        text: 'No hay más cursos'
    }));

    
});
