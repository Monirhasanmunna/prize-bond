import React from "react";

export default function Success({data}){
    const {amount, trx_id} = data
    return (
        <div className="min-h-screen bg-slate-50 flex items-center justify-center px-4 py-10">
            <div className="w-full max-w-[480px] bg-white rounded-2xl shadow-md border border-slate-100">
                {/* Success Icon & Message */}
                <div className="flex flex-col items-center gap-3 pt-10 px-8 text-center">
                    <div className="w-14 h-14 rounded-full bg-emerald-100 flex items-center justify-center">
                        <svg
                            className="w-8 h-8 text-emerald-500"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            strokeWidth="1.5"
                        >
                            <path strokeLinecap="round" strokeLinejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                    </div>

                    <h1 className="text-xl font-semibold text-slate-900">
                        Payment Successful 🎉
                    </h1>
                    <p className="text-slate-500 text-sm leading-relaxed">
                        Your payment has been successfully completed.
                        You’re now subscribed to the Prize Bond service.
                    </p>
                </div>

                {/* Summary Section */}
                <div className="mt-7 mx-5 bg-slate-50 border border-slate-100 rounded-xl p-4 space-y-3">
                    <div className="flex items-center justify-between">
                        <p className="text-sm text-slate-500">Amount Paid</p>
                        <p className="text-base font-semibold text-emerald-500">{amount}</p>
                    </div>
                    <div className="flex items-center justify-between">
                        <p className="text-sm text-slate-500">Transaction ID</p>
                        <p className="text-[0.75rem] font-mono bg-white px-2 py-1 rounded-md text-slate-700 border border-slate-200">
                            {trx_id}
                        </p>
                    </div>
                </div>

                {/* Footer */}
                <div className="pb-5 text-center">
                    <p className="text-[0.7rem] text-slate-400">
                        Prize Bond Notifier • Secure Payment Processed
                    </p>
                </div>
            </div>
        </div>
    )
}
