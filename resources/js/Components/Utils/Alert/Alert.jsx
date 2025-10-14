import {router} from "@inertiajs/react";
import {HSOverlay} from "preline";
import {BsFillQuestionOctagonFill} from "react-icons/bs";
import React, {useState} from "react";
export default function Alert({alertData}){

    const [loading, setLoading] = useState(false);

    function submit(e){
        e.preventDefault();
        if(alertData.method.toLowerCase() === 'delete'){
            router[alertData.method.toLowerCase()](`${alertData.route}`, {
                onStart : () => setLoading(true),

                onSuccess: () => HSOverlay.close("#alert-modal"),

                onFinish : () => {
                    setLoading(false)
                    HSOverlay.close("#alert-modal");
                }
            })
        }else{
            router[alertData.method.toLowerCase()](`${alertData.route}`, {}, {
                onStart : () => setLoading(true),

                onSuccess: () => HSOverlay.close("#alert-modal"),

                onFinish : () => {
                    setLoading(false)
                    HSOverlay.close("#alert-modal");
                }
            })
        }
    }

    return (
        <div id="alert-modal"
             className="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none"
             role="dialog" tabIndex="-1" aria-labelledby="alert-modal-label">
            <div
                className="hs-overlay-animation-target hs-overlay-open:scale-100 hs-overlay-open:opacity-100 scale-95 opacity-0 ease-in-out transition-all duration-200 sm:max-w-sm sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
                <div
                    className="w-full flex flex-col bg-white shadow-sm rounded-sm pointer-events-auto dark:bg-neutral-800 dark:border-neutral-600 dark:shadow-neutral-700/70">
                    <div className="p-4 overflow-y-auto text-center">
                        <form onSubmit={submit}>
                            <div className="w-full p-5 flex items-center justify-center">
                                <BsFillQuestionOctagonFill className={`text-${alertData.color}-600 size-10`} />
                            </div>

                            <div className="w-full tex-center">
                                <h4 className="text-md dark:text-gray-100">{alertData.text}</h4>
                            </div>

                            <div className="w-full p-5 flex items-center justify-center gap-x-4">
                                <button type="button" className="px-3 py-1.5 border cursor-pointer border-gray-400 dark:border-gray-100 dark:text-gray-100 rounded-sm text-sm" aria-label="Close" data-hs-overlay="#alert-modal">No, cancel</button>
                                <button type="submit" className={`px-3 py-1.5 cursor-pointer bg-${alertData.color}-600 text-white border border-${alertData.color}-600 rounded-sm text-sm hover:bg-${alertData.color}-600 duration-150 flex items-center gap-x-2`}>
                                    {
                                        loading === true ? (
                                            <div
                                                className={`animate-spin inline-block size-4 border-[3px] border-current border-t-transparent text-${alertData.color}-300 rounded-full dark:text-blue-500`}
                                                role="status" aria-label="loading">
                                                <span className="sr-only">Loading...</span>
                                            </div>
                                        ): <></>
                                    }
                                    Yes, I'm sure
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    )
}
