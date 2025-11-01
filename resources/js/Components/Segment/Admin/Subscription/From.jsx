import {RxCross2, RxUpload} from "react-icons/rx";
import {useEffect, useRef, useState} from "react";
import {useForm, usePage} from "@inertiajs/react";
import {HSOverlay} from "preline";
import Button from "@/Components/Utils/Button/Button.jsx";
import TextEditor from "@/Components/Utils/TextEditor/TextEditor.jsx";

export default function Form({subscription, setSubscription, subscriptionType}){
    const {data, setData, post, processing, errors, reset} = useForm({
        name: '',
        duration_type: '',
        duration: '',
        base_price: '',
        discount_price: '',
    });

    useEffect(() => {
        if(subscription){
            setData({
                ...data,
                name: subscription.name ?? '',
                duration_type: subscription.duration_type ?? '',
                duration: subscription.duration ?? '',
                base_price: subscription.base_price ?? '',
                discount_price: subscription.discount_price ?? '',
                id: subscription.id ?? ''
            })
        } else {
            setData({
                name: '',
                duration_type: '',
                duration: '',
                base_price: '',
                discount_price: '',
            })
        }
    }, [subscription])


    const handleTextInput = (e) => {
        const {id,value} = e.target

        setData(prevState => ({
            ...prevState,
            [id]: value
        }))
    }


    const handleSubmitForm = (e) => {
        e.preventDefault();

        if(!subscription){
            post(route('admin.subscription.store'), {
                preserveState: true,
                preserveScroll: true,
                onError: () => HSOverlay.open('#subscription-form'),
                onSuccess: () => {
                    HSOverlay.close('#subscription-form')
                    reset()
                    setSubscription(null)
                }
            })
        }else{
            post(route('admin.subscription.update'), {
                preserveState: true,
                preserveScroll: true,
                onError: () => HSOverlay.open('#subscription-form'),
                onSuccess: () => {
                    HSOverlay.close('#subscription-form')
                    reset()
                    setSubscription(null)
                }
            })
        }
    }

    return (
        <div id="subscription-form"
             className="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none"
             role="dialog" tabIndex="-1" aria-labelledby="subscription-form-label">
            <div
                className="hs-overlay-animation-target hs-overlay-open:scale-100 hs-overlay-open:opacity-100 scale-95 opacity-0 ease-in-out transition-all duration-200 sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-56px)] flex items-center">
                <div
                    className="w-full flex flex-col bg-white border border-gray-200 shadow-2xs rounded-sm pointer-events-auto dark:bg-neutral-800 dark:border-neutral-600 dark:shadow-neutral-700/70">
                    <div
                        className="flex justify-between items-center py-3 px-4 border-b border-gray-200 dark:border-neutral-600">
                        <h3 id="subscription-form-label" className="font-bold text-gray-800 text-md dark:text-white">
                            {subscription ? 'Update': 'Create'} Form
                        </h3>
                        <button type="button"
                                onClick={(e) => {
                                    reset()
                                }}
                                className="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200 focus:outline-hidden focus:bg-gray-200 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-700 dark:hover:bg-neutral-600 dark:text-neutral-200 dark:focus:bg-neutral-600"
                                aria-label="Close" data-hs-overlay="#subscription-form">
                            <RxCross2 className={`size-4`} />
                        </button>
                    </div>
                    <div className="p-4 overflow-y-auto">
                        <form onSubmit={handleSubmitForm}  className={`w-full space-y-2`}>

                            <div className="form-control">
                                <label htmlFor="name" className={`label`}>Name <span className={`text-xs text-red-600`}>*</span></label>
                                <input type="text" className={`input`} id={`name`} value={data.name} onChange={handleTextInput} placeholder={`Enter name`}/>
                            </div>

                            <div className="form-control">
                                <label htmlFor="duration_type" className={`label`}>Duration Type <span className={`text-xs text-red-600`}>*</span></label>
                                <select className={`input capitalize`} name="duration_type" id="duration_type" value={data.duration_type} onChange={handleTextInput}>
                                    <option value="" hidden  disabled={true}>Select Once....</option>
                                    {
                                        Object.keys(subscriptionType).map((type) => (
                                            <option value={type}>{type.split('_').join(' ')}</option>
                                        ))
                                    }
                                </select>
                            </div>

                            <div className="form-control">
                                <label htmlFor="duration" className={`label`}>Duration</label>
                                <input type="number" className={`input`} id={`duration`} value={data.duration} onChange={handleTextInput} placeholder={`Enter duration`}/>
                            </div>

                            <div className="form-control">
                                <label htmlFor="base_price" className={`label`}>Base Price <span className={`text-xs text-red-600`}>*</span></label>
                                <input type="number" className={`input`} id={`base_price`} value={data.base_price} onChange={handleTextInput} placeholder={`Enter base price`}/>
                            </div>

                            <div className="form-control">
                                <label htmlFor="discount_price" className={`label`}>Discount Price</label>
                                <input type="number" className={`input`} id={`discount_price`} value={data.discount_price} onChange={handleTextInput} placeholder={`Enter discount price`}/>
                            </div>

                            <div className="form-control pt-4">
                                <div className="w-full flex gap-x-4">
                                    <Button buttonText={!subscription ? 'Create' : 'Update'} isLoading={processing} />
                                    <Button
                                        buttonText={'Cancel'}
                                        type={`button`}
                                        callback={() => HSOverlay.close("#subscription-form")}
                                        className={`bg-red-500 text-white rounded hover:bg-red-600 duration-150`}
                                    />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    )
}
