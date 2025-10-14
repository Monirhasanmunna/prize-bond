import Select from 'react-select';
import {useEffect, useMemo, useState} from "react";

const lightColors = {
    background: '#fff',
    text: '#111827',           // gray-900
    border: '#99A1AF',         // gray-300
    borderFocused: '#3b82f6',  // blue-500
    optionHoverBg: '#3b82f6',
    optionHoverText: '#fff',
    multiValueBg: '#e0e7ff',   // blue-100
    multiValueLabel: '#1e40af' // blue-900
};

const darkColors = {
    background: '#4c5056',     // gray-800
    text: '#e5e7eb',           // gray-200
    border: '#99A1AF',         // gray-600
    borderFocused: '#60a5fa',  // blue-400
    optionHoverBg: '#2563eb',  // blue-600
    optionHoverText: '#fff',
    multiValueBg: '#374151',   // gray-700
    multiValueLabel: '#fff'
};

const customStyles = {
    control: (base, state) => ({
        ...base,
        width: '100%',
        border: `1px solid ${state.isFocused ? colors.borderFocused : colors.border}`,
        borderRadius: '0.125rem',
        boxShadow: 'none',
        backgroundColor: colors.background,
        color: colors.text,
        '&:hover': {
            border: `1px solid ${state.isFocused ? colors.borderFocused : colors.border}`,
        },
    }),
    menu: (base) => ({
        ...base,
        backgroundColor: colors.background,
        color: colors.text,
    }),
    option: (base, state) => ({
        ...base,
        backgroundColor: state.isFocused ? colors.optionHoverBg : colors.background,
        color: state.isFocused ? colors.optionHoverText : colors.text,
        textTransform: 'capitalize',
        cursor: 'pointer',
    }),
    singleValue: (base) => ({
        ...base,
        color: colors.text,
        textTransform: 'capitalize',
    }),
    multiValue: (base) => ({
        ...base,
        backgroundColor: colors.multiValueBg,
    }),
    multiValueLabel: (base) => ({
        ...base,
        color: colors.multiValueLabel,
        textTransform: 'capitalize',
    }),
};

const isDarkMode = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
const colors = isDarkMode ? darkColors : lightColors;


function findIndexes(options, value) {
    if (Array.isArray(value)) {
        return value
            .map(val => options.findIndex(opt => opt.value == val))
            .filter(i => i !== -1);
    } else {
        const idx = options.findIndex(opt => opt.value == value);
        return idx !== -1 ? [idx] : [];
    }
}

export default function SelectComponent({ options, defaultValue, onChange, multiple = false, darkMode = false, isEdited = false}) {

    const indexArr = useMemo(() => findIndexes(options, defaultValue), [options, defaultValue]);

    const computedDefaultValue = multiple
        ? indexArr.map(i => options[i])
        : options[indexArr[0]] || null;

    const handleChange = (selected) => {
        if (multiple) {
            onChange(selected ? selected.map(opt => opt.value) : []);
        } else {
            onChange(selected?.value ?? null);
        }
    };


    return (
        <Select
            isMulti={multiple}
            onChange={handleChange}
            options={options}
            styles={customStyles}
            value={computedDefaultValue}
        />
    );
}
