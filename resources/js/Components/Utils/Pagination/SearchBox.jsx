import { FaSearch } from "react-icons/fa";
import React, { useState } from "react";

function SearchBox(props) {
    const [searchButton, setSearchButton] = useState("search");

    return (
        <div className="flex items-center rounded border border-gray-400 pr-2">
            <input
                className="outline-none border-none focus:ring-1 focus:ring-blue-500 grow py-1 px-2 dark:bg-neutral-100"
                type="text"
                placeholder="Search..."
                value={props.searchText}
                onChange={(e) => {
                    setSearchButton("search");
                    props.onInputChange(e);
                }}
                onKeyDown={(e) => {
                    if (e.key === "Enter") {
                        if (props.searchText.length) {
                            setSearchButton("cancel");
                            props.onSearch();
                        } else {
                            setSearchButton("search");
                            props.onSearchCancel();
                        }
                    }
                }}
            />
            {searchButton === "search" ? (
                <button
                    onClick={() => {
                        if (props.searchText.length) {
                            setSearchButton("cancel");
                            props.onSearch();
                        }
                    }}
                    className="pl-2 pr-1 font-bold text-gray-400 hover:text-gray-600 dark:hover:text-neutral-50"
                    aria-label="Search"
                    type="button"
                >
                    <FaSearch className={`size-3`} />
                </button>
            ) : (
                <button
                    onClick={() => {
                        setSearchButton("search");
                        props.onSearchCancel();
                    }}
                    className="px-2 font-bold text-gray-400 hover:text-gray-600 dark:hover:text-neutral-50"
                    aria-label="Cancel Search"
                    type="button"
                >
                    ×
                </button>
            )}
        </div>
    );
}

export default SearchBox;
