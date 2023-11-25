import { useState } from "react";

export const useInput = () => {
    const [value, setValue] = useState({ naira: "", usd: "" });

    const handleInputChange = e => {
        let inputValue = e.target.value;

        // if the first input value is zero, add the next number
        // if (inputValue.length === 2 && inputValue[0] === "0") {
        //     inputValue = inputValue[1];
        // }

        // Allow only decimal values with commas

        const decimalValue = inputValue
            .replace(/[^\d.]/g, "") // Allow only digits and decimal point
            .replace(/^(\d*\.\d*)\./, "$1") // Remove multiple decimal points
            .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") // Add commas
            .replace(/(\.\d*)(,)/g, "$1");

        if (e.target.name === "convert") {
            setValue(val => ({
                ...val,
                naira: decimalValue,
            }));
        }

        if (e.target.name === "to") {
            setValue(val => ({
                ...val,
                usd: decimalValue,
            }));
        }
    };

    return { value, handleInputChange };
};
