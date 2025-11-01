import {useEffect, useState} from "react";
import {setPaginationFromSessionStorage} from "@/helpers/helper.js";
import Main from "@/Layouts/Backend/Main.jsx";
import {router} from "@inertiajs/react";
import LengthDropdown from "@/Components/Utils/Pagination/LengthDropdown.jsx";
import SearchBox from "@/Components/Utils/Pagination/SearchBox.jsx";
import StatusFilter from "@/Components/Utils/Pagination/StatusFilter.jsx";
import {STATUS_DELETED} from "@/helpers/const.js";
import Summary from "@/Components/Utils/Pagination/Summary.jsx";
import Navigation from "@/Components/Utils/Pagination/Navigation.jsx";
import Alert from "@/Components/Utils/Alert/Alert.jsx";
import {FaTrash} from "react-icons/fa";
import {HSOverlay} from "preline";
import Form from "@/Components/Segment/Admin/Subscription/From.jsx";
import {MdEditSquare} from "react-icons/md";


export default function Page({data: subscriptionListData}){
    const [pagination, setPagination] = useState({page: subscriptionListData.page, length: subscriptionListData.length})
    const [searchText, setSearchText] = useState('')
    const [go, setGo] = useState(false)
    const [subscription, setSubscription] = useState(null)
    const [alertData, setAlertData] = useState({route : '', color : '', method : '', text : ''});

    useEffect(() => {
        setPaginationFromSessionStorage(setPagination, 'subscription_pagination', {
            page: subscriptionListData?.page, length: subscriptionListData?.length, status: subscriptionListData.status, searchText: ""
        }, )
    }, []);

    useEffect(() => {
        if (pagination.searchText !== searchText) {
            setSearchText(pagination.searchText)
        }
    }, [pagination]);

    useEffect(() => {
        if (pagination?.page && pagination?.length && go) {
            setGo(false)
            const queryParams = {
                page: pagination.page,
                length: pagination.length,
            };

            if (pagination.status) queryParams.status = pagination.status;
            if (pagination.searchText) queryParams.search = pagination.searchText;

            router.get(route('admin.subscription.list', queryParams))

            sessionStorage.setItem('subscription_pagination', JSON.stringify(pagination))
        }
    }, [go]);


    const editSubscription = (subscription) => {
        setSubscription({
            ...subscription,
        })
        HSOverlay.open('#subscription-form')
    }


    const alert = (id) => {
        setAlertData({
            ...alertData,
            route : route('admin.subscription.delete', id),
            color : 'red',
            method : 'delete',
            text : 'Are you sure you want to delete this ?'
        })

        HSOverlay.open('#alert-modal')
    }

    return (
        <>
            <Main>
                <div className="w-full p-4 rounded bg-gray-100 shadow">
                    <ol className="flex items-center whitespace-nowrap">
                        <li className="flex items-center text-sm text-gray-800 dark:text-neutral-400">
                            Dashboard
                            <svg className="shrink-0 mx-3 overflow-visible size-2.5 text-gray-400 dark:text-neutral-500" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5 1L10.6869 7.16086C10.8637 7.35239 10.8637 7.64761 10.6869 7.83914L5 14" stroke="currentColor" strokeWidth="2" strokeLinecap="round"/>
                            </svg>
                        </li>
                        <li className="flex items-center text-sm text-gray-800 dark:text-neutral-400">
                            Subscription
                            <svg className="shrink-0 mx-3 overflow-visible size-2.5 text-gray-400 dark:text-neutral-500" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5 1L10.6869 7.16086C10.8637 7.35239 10.8637 7.64761 10.6869 7.83914L5 14" stroke="currentColor" strokeWidth="2" strokeLinecap="round"/>
                            </svg>
                        </li>
                        <li className="text-sm font-semibold text-gray-800 truncate dark:text-neutral-400" aria-current="page">
                            List
                        </li>
                    </ol>
                </div>

                <div className="w-full border border-gray-300 rounded mt-5">
                    <div className="flex items-center justify-between border-b border-gray-300 px-5 py-3">
                        <div className="flex items-center gap-x-6">
                            <h2 className="font-medium text-xl leading-6 text-neutral-500 dark:text-neutral-300">Subscription</h2>
                        </div>
                        <button onClick={() => setSubscription(null)} type="button"
                                className="py-1.5 px-5 inline-flex items-center gap-x-2 text-sm font-medium rounded bg-blue-600 text-white hover:bg-blue-700"
                                aria-haspopup="dialog" aria-expanded="false" aria-controls="subscription-form"
                                data-hs-overlay="#subscription-form">
                            Add New
                        </button>
                    </div>

                    <div className="w-full p-5">
                        <div className="flex flex-col md:flex-row md:justify-between  items-center gap-3 my-4 text-[.75rem]">
                            <LengthDropdown
                                callback={(value) => {
                                    setPagination(prevState => ({...prevState, page: 1, length: value}))
                                    setGo(true)
                                }}
                                placeholder={pagination.length}
                            />
                            <div className="flex flex-col md:flex-row items-center md:justify-end gap-3 flex-wrap">
                                <StatusFilter status={subscriptionListData.subscriptionStatus} pagination={pagination} setPagination={setPagination} setGo={setGo} segment="Subscription" />

                                <SearchBox
                                    searchText={searchText}
                                    onInputChange={(e) => {
                                        setSearchText(e.target.value);
                                    }}
                                    onSearch={() => {
                                        setPagination((state) => ({ ...state, page: 1, searchText }));
                                        setGo(true);
                                    }}
                                    onSearchCancel={() => {
                                        setSearchText("");
                                        setPagination((state) => ({ ...state, page: 1, searchText: "" }));
                                        setGo(true);
                                    }}
                                />
                            </div>
                        </div>

                        <div className="overflow-x-auto">
                            <table className="min-w-full border border-neutral-300 dark:border-neutral-400 border-collapse text-sm">
                                <thead className="bg-gray-100 dark:bg-neutral-700">
                                <tr>
                                    <th className=" px-4 py-2 text-left font-semibold text-gray-600 dark:text-neutral-200 border border-neutral-300 dark:border-neutral-400">
                                        #
                                    </th>
                                    <th className="w-[15%] px-4 py-2 text-left font-semibold text-gray-600 dark:text-neutral-200 border border-neutral-300 dark:border-neutral-400">
                                        Name
                                    </th>
                                    <th className="px-4 py-2 text-left font-semibold text-gray-600 dark:text-neutral-200 border border-neutral-300 dark:border-neutral-400">
                                        Duration Type
                                    </th>
                                    <th className="px-4 py-2 text-left font-semibold text-gray-600 dark:text-neutral-200 border border-neutral-300 dark:border-neutral-400">
                                        Duration
                                    </th>
                                    <th className="px-4 py-2 text-left font-semibold text-gray-600 dark:text-neutral-200 border border-neutral-300 dark:border-neutral-400">
                                        Base Price
                                    </th>
                                    <th className="px-4 py-2 text-left font-semibold text-gray-600 dark:text-neutral-200 border border-neutral-300 dark:border-neutral-400">
                                        Discount Price
                                    </th>
                                    <th className="px-4 py-2 text-left font-semibold text-gray-600 dark:text-neutral-200 border border-neutral-300 dark:border-neutral-400">
                                        Status
                                    </th>
                                    <th className="w-[10%] px-4 py-2 text-left font-semibold text-gray-600 dark:text-neutral-200 border border-neutral-300 dark:border-neutral-400">
                                        Action
                                    </th>
                                </tr>
                                </thead>

                                <tbody>
                                {
                                    subscriptionListData.subscriptions.map((subscription, i) => (
                                        <tr key={subscription.id} className={`hover:bg-gray-100 dark:hover:bg-neutral-600 ${subscription.status === STATUS_DELETED ? 'bg-red-300 hover:bg-red-300 dark:hover:bg-red-300' : ''}`}>
                                            <td className="px-4 py-2 font-medium text-gray-800 dark:text-neutral-200 border border-neutral-300 dark:border-neutral-400">
                                                {i+1}
                                            </td>
                                            <td className="px-4 py-2 font-medium text-gray-800 dark:text-neutral-200 border border-neutral-300 dark:border-neutral-400">
                                                {subscription.name}
                                            </td>
                                            <td className="px-4 py-2 font-medium text-gray-800 dark:text-neutral-200 capitalize border border-neutral-300 dark:border-neutral-400">
                                                {subscription.duration_type.split('_').join(' ')}
                                            </td>
                                            <td className="px-4 py-2 font-medium text-gray-800 dark:text-neutral-200 border border-neutral-300 dark:border-neutral-400">
                                                {subscription.duration}
                                            </td>
                                            <td className="px-4 py-2 font-medium text-gray-800 dark:text-neutral-200 border border-neutral-300 dark:border-neutral-400">
                                                {subscription.base_price}
                                            </td>
                                            <td className="px-4 py-2 font-medium text-gray-800 dark:text-neutral-200 border border-neutral-300 dark:border-neutral-400">
                                                {subscription.discount_price}
                                            </td>
                                            <td className="px-4 py-2 font-medium text-gray-800 dark:text-neutral-200 border border-neutral-300 dark:border-neutral-400">
                                                {
                                                    subscription.status === STATUS_DELETED ? (<span className={`capitalize`}>{subscription.status}</span>) : (
                                                        <select
                                                            className={`py-1 px-2 border w-auto min-w-[150px] text-[14px] outline-none focus:ring-0 text-neutral-700 dark:text-neutral-600 border-neutral-300 dark:border-neutral-400`}
                                                            value={subscription.status}
                                                            onChange={(e) => {
                                                                router.post(route('admin.subscription.change_status'), {
                                                                    id: subscription.id, status: e.target.value
                                                                })
                                                            }}
                                                        >
                                                            {Object.keys(subscriptionListData.subscriptionStatus).filter(key => key !== STATUS_DELETED).map((key) => {
                                                                const statusText = subscriptionListData.subscriptionStatus[key];
                                                                return <option key={key} value={key}>{statusText}</option>
                                                            })}
                                                        </select>
                                                    )
                                                }
                                            </td>
                                            <td className="px-4 py-2 font-medium text-gray-800 dark:text-neutral-200 border border-neutral-300 dark:border-neutral-400">
                                                <div className="w-full flex gap-x-3">
                                                    <button className="cursor-pointer text-white border-green-600"
                                                            onClick={() => editSubscription(subscription)}>
                                                        <MdEditSquare className={`text-blue-400 text-lg`}/>
                                                    </button>
                                                    <button className=" cursor-pointer text-white border-green-600"
                                                            onClick={() => alert(subscription.id)}>
                                                        <FaTrash className={`text-red-400 text-lg`}/>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    ))
                                }
                                </tbody>
                            </table>

                            <div className="py-3 sm:px-6">
                                <div className="flex flex-col sm:flex-row gap-2 items-center justify-between">
                                    <Summary
                                        page={subscriptionListData.page}
                                        length={subscriptionListData.length}
                                        count={subscriptionListData.count}
                                    />
                                    <Navigation
                                        page={pagination.page}
                                        length={subscriptionListData.length}
                                        count={subscriptionListData.count}
                                        callback={cp => {
                                            setPagination(state => ({...state, page: cp}))
                                            setGo(true)
                                        }}
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </Main>
            <Form subscription={subscription} setSubscription={setSubscription} subscriptionType={subscriptionListData.subscriptionType} />
            <Alert alertData={alertData} />
        </>
)
}
