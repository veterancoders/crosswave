import { useData } from "./hooks/useData";

export default function DataFectchTest() {
    const { data, isLoading, isError } = useData();

    if (isLoading) return <h2>Loading....</h2>;

    if (isError) return <h2>An error occured</h2>;

    return (
        <div>
            <h1>test data fetch</h1>

            <button className='bg-slate-400 text-lg'>Fetch data</button>

            <p>{new Date(data.datetime).toLocaleString()}</p>
            <p>{data.timezone}</p>
        </div>
    );
}
