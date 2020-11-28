import {jest} from "@jest/globals";

export const InertiaFormMock = {
    hasErrors: jest.fn(),
    error: jest.fn(),
    post: jest.fn(),
    put: jest.fn(),
    delete: jest.fn(),
    successful: jest.fn()
}

export const InertiaForm = {
    install(app) {
        if (app.version.split('.')[0] === 3) {
            Object.defineProperty(app.config.globalProperties.$inertia, 'form', {
                value: (data = {}, options = {}) => {
                    return InertiaFormMock
                }});
        } else {
            app.prototype.$inertia.form = (data = {}, options = {}) => {
                return InertiaFormMock
            };
        }
    },
}
