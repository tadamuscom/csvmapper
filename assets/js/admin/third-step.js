document.addEventListener( 'DOMContentLoaded', () => {
    const form = document.getElementById( 'csvm-step-three-form' );
    const loader = document.getElementById( 'csvm-loader-wrap' );
    const heading = document.getElementById( 'csvm-step-three-heading' );
    const switcher = document.getElementById( 'csvm-execution-type' );
    const numberOfRows = document.getElementById( 'csvm-number-of-rows' );
    const rowSelect = document.getElementById( 'csvm-number-of-process-select' );

    const triggerError = ( node, message ) => {
        node.classList.add( 'csvm-error-field' );

        const errorMessage = document.createElement( 'p' );
        errorMessage.classList.add( 'csvm-error-message' );
        errorMessage.innerText = message;

        node.parentNode.appendChild( errorMessage );

        return false;
    }

    const formValidation = ( event ) => {
        if( switcher.value.length <= 0 ){
            return triggerError( switcher, 'You must select an import method' );
        }

        if( switcher.value === 'ajax' || switcher.value === 'wp-cron' ){
            if( rowSelect.value.length <= 0 || typeof rowSelect != 'number' ){
                return triggerError( rowSelect, 'You must select a number of rows' );
            }
        }

        return true;
    }

    form.addEventListener( 'submit', ( event ) => {
        if( switcher.value === 'ajax' ){
            event.preventDefault();

            if( formValidation( event ) ){
                form.classList.add( 'csvm-d-none' );
                heading.classList.add( 'csvm-d-none' );
                loader.classList.remove( 'csvm-d-none' );
            }
        }
    } );

    switcher.addEventListener( 'change', () => {
        if( switcher.value === 'ajax' || switcher.value === 'wp-cron' ){
            if( numberOfRows.classList.contains( 'csvm-d-none' ) ){
                numberOfRows.classList.remove( 'csvm-d-none' );
            }
        }else{
            if( ! numberOfRows.classList.contains( 'csvm-d-none' ) ){
                numberOfRows.classList.add( 'csvm-d-none' );
            }
        }
    } );
} );