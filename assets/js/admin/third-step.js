document.addEventListener( 'DOMContentLoaded', () => {
    const form = document.getElementById( 'csvm-step-three-form' );
    const switcher = document.getElementById( 'csvm-execution-type' );
    const numberOfRows = document.getElementById( 'csvm-number-of-rows' );

    form.addEventListener( 'submit', ( event ) => {
        if( switcher.value === 'ajax' ){
            event.preventDefault();
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