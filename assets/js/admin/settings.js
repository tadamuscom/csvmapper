document.addEventListener(
    'DOMContentLoaded', () => {
        const enableCronTask = document.getElementById('csvm-enable-cron');
        const intervalNumber = document.getElementById('csvm-cron-interval-number');
        const intervalPeriod = document.getElementById('csvm-cron-interval-period');

        enableCronTask.addEventListener( 'click', () => {
            if( intervalNumber.getAttribute( 'disabled' ) ) {
                intervalNumber.removeAttribute( 'disabled' );
            }else{
                intervalNumber.setAttribute( 'disabled', true );
            }

            if( intervalPeriod.getAttribute( 'disabled' ) ) {
                intervalPeriod.removeAttribute( 'disabled' );
            }else{
                intervalPeriod.setAttribute( 'disabled', true );
            }
		} );
    }
);