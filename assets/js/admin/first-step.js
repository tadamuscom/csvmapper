document.addEventListener( 'DOMContentLoaded', () => {
    const typeSelect        = document.getElementById( 'csv-import-type' );
    const batchCheck        = document.getElementById( 'csvm-enable-batched-rows' )
    const postMetaWrap      = document.getElementById( 'csvm-post-meta-wrap' );
    const userMetaWrap      = document.getElementById( 'csvm-user-meta-wrap' );
    const postWrap          = document.getElementById( 'csvm-post-wrap' );
    const customTableWrap   = document.getElementById( 'csvm-custom-table-wrap' );
    const batchedRowsWrap   = document.getElementById( 'csvm-batched-rows-wrap' );

    const hideElement = ( element ) => {
        if( ! element.classList.contains( 'csvm-d-none' ) ){
            element.classList.add( 'csvm-d-none' );
        }
    }

    const showElement = ( element ) => {
        if( element.classList.contains( 'csvm-d-none' ) ){
            element.classList.remove( 'csvm-d-none' );
        }
    }

    const resetTypeScenes = () => {
        hideElement(postMetaWrap);
        hideElement(userMetaWrap);
        hideElement(postWrap);
        hideElement(customTableWrap);
    }

    if( typeSelect ){
        typeSelect.addEventListener( 'change', (e) => {
            resetTypeScenes();

            if( typeSelect.value === 'post-meta' )      showElement(postMetaWrap);
            if( typeSelect.value === 'user-meta' )      showElement(userMetaWrap);
            if( typeSelect.value === 'posts' )           showElement(postWrap);
            if( typeSelect.value === 'custom-table' )   showElement(customTableWrap);
        } );
    }

    if( batchCheck ){
        batchCheck.addEventListener('change' , (e) => {
            batchedRowsWrap.classList.toggle('csvm-d-none');
        } )
    }
} );