document.addEventListener( 'DOMContentLoaded', () => {
    const listSelectors = document.querySelectorAll( '.csvm-open-field-list' );
    const listItems = document.querySelectorAll( '.csvm-field-list-link' );

    if( listSelectors ){
        listSelectors.forEach( ( element ) => {
            element.addEventListener( 'click' , ( e ) => {
                const group = element.getAttribute( 'group' );
                let type;

                if( element.getAttribute( 'group-type' ) === 'meta-name' )  type = 'name';
                if( element.getAttribute( 'group-type' ) === 'meta-value' ) type = 'value';

                const list = document.querySelector( '#csvm-' + type + '-field-list-' + group );

                list.classList.toggle( 'csvm-d-none' );
            } );
        } );
    }

    if( listItems ){
        listItems.forEach( ( element ) => {
            element.addEventListener( 'click', ( e ) => {
                const group = element.getAttribute( 'group' );
                const mappingValue = element.getAttribute( 'mapping-value' );

                let type;

                if( element.getAttribute( 'group-type' ) === 'meta-name' )  type = 'name';
                if( element.getAttribute( 'group-type' ) === 'meta-value' ) type = 'value';

                const field = document.querySelector( '#meta-' + type + '-' + group );

                field.value += mappingValue;
                element.classList.add('csvm-d-none');
            } );
        });
    }
}, false );