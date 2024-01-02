document.addEventListener( 'DOMContentLoaded', () => {
    const listSelectors = document.querySelectorAll( '.csvm-open-field-list' );
    const listItems     = document.querySelectorAll( '.csvm-field-list-link' );

    const handleNewBoxes = () => {
        const newSelectors  = document.querySelectorAll( '.csvm-open-field-list' );
        const newLists      = document.querySelectorAll( '.csvm-field-list-link' );

        newSelectors.forEach( (element) => {
            const newElement = element.cloneNode( true );
            element.replaceWith( newElement );

            newElement.addEventListener('click', () => {
                handleSelectors(element);
            });
        } );

        newLists.forEach( ( element ) => {
            element.addEventListener( 'click', () => {
                const group         = element.getAttribute( 'group' );
                const mappingValue  = element.getAttribute( 'mapping-value' );

                const field = document.getElementById( group );

                field.value += '{' + mappingValue + '}';
                element.parentNode.classList.add( 'csvm-d-none' );
                element.parentNode.parentNode.classList.add( 'csvm-d-none' );
            } );
        });
    }

    const handleSelectors = ( element ) => {
        const group = element.getAttribute( 'group' );
        const list = document.querySelector( '#csvm-field-list-' + group );

        list.classList.toggle( 'csvm-d-none' );
    }

    window.addEventListener( 'csvm-box-created', handleNewBoxes );
    window.addEventListener( 'csvm-box-removed', handleNewBoxes);

    if( listSelectors || listItems ){
        handleNewBoxes();
    }
}, false );