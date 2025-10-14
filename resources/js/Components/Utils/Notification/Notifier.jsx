
import { usePage } from "@inertiajs/react";
import { notify } from "@/helpers/helper.js";
import { ToastContainer } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";
import {useEffect} from "react";

export default function Notifier() {
    const {message, errors} = usePage().props

    useEffect(() => {
        if (message) {
            notify({ success: true, message });
        }
    }, [message]);

    useEffect(() => {
        if (errors && Object.keys(errors).length > 0) {
            Object.keys(errors).forEach((key) => {
                notify({ message: errors[key] });
            });
        }
    }, [errors]);

    return <ToastContainer position="top-right" autoClose={2000} />;
}
