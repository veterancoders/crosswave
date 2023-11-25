import { createContext, useReducer, useState } from "react";

export const BackdropContext = createContext({});

// Reducer function
const reducer = (state, action) => {
    switch (action.type) {
        case "CLOSE":
            return {
                mobileMenu: false,
                transferModal: false,
                convertModal: false,
                backdrop: false,
            };
        case "MOBILEMENU":
            return { ...state, mobileMenu: true, backdrop: true };
        case "TRANSFER":
            return { ...state, transferModal: true, backdrop: true };
        case "CONVERT":
            return { ...state, convertModal: true, backdrop: true };
        default:
            return state;
    }
};

const BackdropProvider = ({ children }) => {
    const [backdropIsOpen, setBackdropIsOpen] = useState(false);
    const [modals, dispatch] = useReducer(reducer, {
        mobileMenu: false,
        transferModal: false,
        convertModal: false,
        backdrop: false,
    });

    return (
        <BackdropContext.Provider
            value={{ backdropIsOpen, setBackdropIsOpen, modals, dispatch }}>
            {children}
        </BackdropContext.Provider>
    );
};

export default BackdropProvider;
