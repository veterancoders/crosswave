import useSWR from "swr";

const fetcher = url => fetch(url).then(res => res.json());

export function useData() {
    const { data, error, isLoading } = useSWR(
        `
        http://worldtimeapi.org/api/timezone/Africa/Lagos`,
        fetcher,
        { revalidateIfStale: false, revalidateOnFocus: false }
    );

    return { data, isError: error, isLoading };
}
