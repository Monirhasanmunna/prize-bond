function LengthDropdown(props) {
    return (
        <select
            onChange={e => props.callback(e.target.value)}
            value={props.placeholder}
            className={`px-1.5 py-1 w-[100px] rounded border-gray-400 outline-none focus:outline-none focus:ring-0 ${props.class}`}
        >
            {props.options ?
                props.options.map((option, index) => (
                    <option key={option} value={option}> {option}</option>
                ))
                : (
                    <>
                        <option value={25}> {25}</option>
                        <option value={50}> {50}</option>
                        <option value={100}> {100}</option>
                        <option value={250}> {250}</option>
                        <option value={500}> {500}</option>
                    </>
                )
            }
        </select>
    );
}

export default LengthDropdown;

