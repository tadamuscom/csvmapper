document.addEventListener( 'DOMContentLoaded', () => {
    const boxesWrap = document.getElementById( 'csvm-meta-boxes' );
    const plusSign = document.getElementById( 'csvm-controls-plus' );
    const minusSign = document.getElementById( 'csvm-controls-minus' );

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

        return returnable;
    };

    const createHeadersToggle = () => {
        const returnable = document.createElement( 'a' );
        returnable.setAttribute( 'href', 'javascript:void(0)' );
        returnable.classList.add( 'csvm-open-field-list' );

        const innerSpan = document.createElement( 'span' );
        innerSpan.innerTest = '{$}';
        returnable.appendChild( innerSpan );

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

        const nameHeadersToggle = createHeadersToggle();
        firstInnerFormGroup.appendChild( nameHeadersToggle );

        const secondFormGroup = createFormGroup();
        wrap.appendChild( secondFormGroup );

        const valueLabel = createLabel( valueID, 'Meta Value' );
        secondFormGroup.appendChild( valueLabel );

        const secondInnerFormGroup = createInnerFormGroup();
        secondFormGroup.appendChild( secondInnerFormGroup );

        const valueInput = createInput( 'text', valueID, valueID );
        secondInnerFormGroup.appendChild( valueInput );

        const valueHeadersToggle = createHeadersToggle();
        secondInnerFormGroup.appendChild( valueHeadersToggle );

        boxesWrap.appendChild( wrap );

        const event = new Event( 'csvm-box-created' );
        window.dispatchEvent( event );
    }

    const deleteBox = () => {
        const lastBox = document.getElementById( 'csvm-meta-box-' + boxesCount );
        --boxesCount;

        lastBox.remove();
    }

    createBox();

    plusSign.addEventListener( 'click', () => {
        createBox();
    } );

    minusSign.addEventListener( 'click', () => {
        deleteBox();
    } );
} );