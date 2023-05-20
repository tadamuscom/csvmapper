document.addEventListener( 'DOMContentLoaded', () => {
    const boxesWrap = document.getElementById( 'csvm-meta-boxes' );

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

    const createControls = () => {
        const returnable = document.createElement( 'div' );
        returnable.classList.add( 'csvm-meta-boxes-controls' );

        const plusSign = document.createElement( 'p' );
        plusSign.innerText = '+'
        returnable.appendChild( plusSign );

        const minusSign = document.createElement( 'p' );
        minusSign.innerText = '-'
        returnable.appendChild( minusSign );

        return returnable;
    }

    const createBox = () => {
        boxesCount = ++boxesCount;

        const nameID = 'meta-name-' + boxesCount;
        const valueID = 'meta-value-' + boxesCount;

        const wrap = document.createElement( 'div' );
        wrap.classList.add( 'csvm-meta-box' );

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

        const controls = createControls();
        wrap.appendChild( controls );

        boxesWrap.appendChild( wrap );
    }

    createBox();
} );