document.addEventListener( 'DOMContentLoaded', () => {
    const listSelectors = document.querySelectorAll( '.csvm-open-field-list' );
    const listItems = document.querySelectorAll( '.csvm-field-list-link' );

    if( listSelectors ){
        listSelectors.forEach( ( element ) => {
            element.addEventListener( 'click' , ( e ) => {
                const group = element.getAttribute( 'group' );

                const list = document.querySelector( '#csvm-field-list-' + group );

                list.classList.toggle( 'csvm-d-none' );
            } );
        } );
    }

    if( listItems ){
        listItems.forEach( ( element ) => {
            element.addEventListener( 'click', ( e ) => {
                const group = element.getAttribute( 'group' );
                const mappingValue = element.getAttribute( 'mapping-value' );

                const field = document.querySelector( '#value-' + group );

                field.value += mappingValue;
                element.classList.add( 'csvm-d-none' );
            } );
        });
    }
}, false );