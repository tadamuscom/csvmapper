document.addEventListener( 'DOMContentLoaded', () => {
    const boxesWrap = document.getElementById( 'csvm-meta-boxes' );
    const plusSign = document.getElementById( 'csvm-controls-plus' );
    const minusSign = document.getElementById( 'csvm-controls-minus' );
    const headersElement = document.getElementById( 'csvm-headers-list' );
    const headersSlugElement = document.getElementById( 'csvm-headers-slug-list' );
    const headers = JSON.parse( headersElement.value );
    const headersSlug = JSON.parse( headersSlugElement.value );

    let boxesCount = 0;

    const createFormGroup = () => {
        const returnable = document.createElement( 'div' );
        returnable.classList.add( 'csvm-forum-group' );

        return returnable;
    };

    const createInnerFormGroup = () => {
        const returnable = document.createElement( 'div' );
        returnable.classList.add( 'csvm-inner-forum-group' );

        return returnable;
    };

    const createLabel = ( forValue, textValue ) => {
        const returnable = document.createElement( 'label' );
        returnable.setAttribute( 'for', forValue );
        returnable.innerText = textValue;

        return returnable;
    };

    const createInput = ( type, id, name ) => {
        const returnable = document.createElement( 'input' );
        returnable.setAttribute( 'type', type );
        returnable.setAttribute( 'id', id );
        returnable.setAttribute( 'name', name );
        returnable.setAttribute( 'required', '' );

        return returnable;
    };

    const createHeadersToggle = ( type ) => {
        const returnable = document.createElement( 'a' );
        returnable.setAttribute( 'href', 'javascript:void(0)' );
        returnable.classList.add( 'csvm-open-field-list' );
        returnable.setAttribute( 'group', 'meta-' + type + '-' + boxesCount );

        const innerSpan = document.createElement( 'span' );
        innerSpan.innerText = '{$}';
        returnable.appendChild( innerSpan );

        return returnable;
    }

    const createHeadersList = ( type ) => {
        const returnable = document.createElement( 'div' );
        returnable.id = 'csvm-field-list-meta-' + type + '-' + boxesCount;
        returnable.classList.add( 'csvm-field-list' );
        returnable.classList.add( 'csvm-d-none' );

        headers.forEach( ( element, index ) => {
            const paragraph = document.createElement( 'p' );
            returnable.appendChild( paragraph );

            const link = document.createElement( 'a' );
            link.setAttribute( 'href', 'javascript:void(0)' );
            link.setAttribute( 'csvm-slug', headersSlug[index] );
            link.setAttribute( 'mapping-value', headersSlug[index] );
            link.setAttribute( 'group', 'meta-' + type + '-' + boxesCount );
            link.classList.add( 'csvm-field-list-link' );
            link.innerText = element;

            paragraph.appendChild( link );
        } );

        return returnable;
    }

    const createBox = () => {
        boxesCount = ++boxesCount;

        const nameID = 'meta-name-' + boxesCount;
        const valueID = 'meta-value-' + boxesCount;

        const wrap = document.createElement( 'div' );
        wrap.classList.add( 'csvm-meta-box' );
        wrap.id = 'csvm-meta-box-' + boxesCount

        const firstFormGroup = createFormGroup();
        wrap.appendChild( firstFormGroup );

        const nameLabel = createLabel( nameID, 'Meta Name' );
        firstFormGroup.appendChild( nameLabel );

        const firstInnerFormGroup = createInnerFormGroup();
        firstFormGroup.appendChild( firstInnerFormGroup );

        const nameInput = createInput( 'text', nameID, nameID  );
        firstInnerFormGroup.appendChild( nameInput );

        const nameHeadersToggle = createHeadersToggle( 'name' );
        firstInnerFormGroup.appendChild( nameHeadersToggle );

        const nameHeadersList = createHeadersList( 'name' );
        firstFormGroup.appendChild( nameHeadersList );

        const secondFormGroup = createFormGroup();
        wrap.appendChild( secondFormGroup );

        const valueLabel = createLabel( valueID, 'Meta Value' );
        secondFormGroup.appendChild( valueLabel );

        const secondInnerFormGroup = createInnerFormGroup();
        secondFormGroup.appendChild( secondInnerFormGroup );

        const valueInput = createInput( 'text', valueID, valueID );
        secondInnerFormGroup.appendChild( valueInput );

        const valueHeadersToggle = createHeadersToggle( 'value' );
        secondInnerFormGroup.appendChild( valueHeadersToggle );

        const valueHeadersList = createHeadersList( 'value' );
        secondFormGroup.appendChild( valueHeadersList );

        boxesWrap.appendChild( wrap );

        const event = new Event( 'csvm-box-created' );
        window.dispatchEvent( event );
    }

    const deleteBox = () => {
        if( boxesCount <= 1 ) {
            return;
        }

        const lastBox = document.getElementById( 'csvm-meta-box-' + boxesCount );
        --boxesCount;

        lastBox.remove();

        const event = new Event( 'csvm-box-removed' );
        window.dispatchEvent( event );
    }

    createBox();

    plusSign.addEventListener( 'click', () => {
        createBox();
    } );

    minusSign.addEventListener( 'click', () => {
        deleteBox();
    } );
} );