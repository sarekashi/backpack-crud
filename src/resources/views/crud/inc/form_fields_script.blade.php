<script>
    /**
     * A front-end representation of a Backpack field, with its main components.
     *
     * Makes it dead-simple for the developer to perform the most common
     * javascript manipulations, and makes it easy to do custom stuff
     * too, by exposing the main components (name, wrapper, input).
     */
    class CrudField {
        constructor(fieldName) {
            this.name = fieldName;
            this.wrapper = document.querySelector(`[bp-field-name="${this.name}"]`);
            this.input = this.wrapper?.querySelector('[bp-field-main-input]') || document.querySelector(`[bp-field-name="${this.name}"] input, [bp-field-name="${this.name}"] textarea, [bp-field-name="${this.name}"] select`);
        }

        get value() {
            let value = this.input?.value;

            // Parse the value if it's a number
            if (value.length && !isNaN(value)) {
                value = Number(value);
            }

            return value;
        }

        set value(value) {
            this.input.value = value;
        }

        change(closure) {
            const fieldChanged = event => {
                const wrapper = this.input.closest('[bp-field-wrapper=true]');
                const name = wrapper.getAttribute('bp-field-name');
                const type = wrapper.getAttribute('bp-field-type');
                const value = this.value;

                closure(event, value, name, type);
            };

            if (this.input) {
                this.input.addEventListener('input', fieldChanged, false);
                // this.input.addEventListener('change', fieldChanged, false);
                $(this.input).change(fieldChanged);

                fieldChanged();
            }

            return this;
        }

        onChange(closure) {
            this.change(closure);
        }

        show(value = true) {
            if(this.wrapper) {
                this.wrapper.style.display = value ? 'block' : 'none';
            }
            return this;
        }

        hide() {
            return this.show(false);
        }

        enable(value = true) {
            if(value) {
                this.input?.removeAttribute('disabled');
                // this.input.dispatchEvent(new CustomEvent('backpack:field.enabled', { bubbles: true }));
                $(this.input).trigger('backpack:field.enabled');
            } else {
                this.input?.setAttribute('disabled', 'disabled');
                // this.input.dispatchEvent(new CustomEvent('backpack:field.disabled', { bubbles: true }));
                $(this.input).trigger('backpack:field.disabled');
            }

            return this;
        }

        disable() {
            return this.enable(false);
        }

        require(value = true) {
            this.wrapper?.classList.toggle('required', value);
            return this;
        }

        unrequire() {
            return this.require(false);
        }

        check(value = true) {
            this.wrapper.querySelectorAll('input[type=checkbox]').forEach(checkbox => {
                checkbox.checked = value;
                checkbox.dispatchEvent(new Event('change'));
            });
            return this;
        }

        uncheck() {
            return this.check(false);
        }
    }

    /**
     * Window functions that help the developer easily select one or more fields.
     */
    window.crud = {
        ...window.crud,

        // Fields map
        map: new Map(),

        // Create a field from a given name
        field: fieldName => {
            if(!window.crud.map.has(fieldName)) {
                window.crud.map.set(fieldName, new CrudField(fieldName));
            }

            return window.crud.map.get(fieldName);
        },

        // Create all fields from a given name list
        fields: fieldNamesArray => fieldNamesArray.map(fieldName => window.crud.field(fieldName)),
    };
</script>