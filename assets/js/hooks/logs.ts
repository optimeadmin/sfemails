import { useQuery } from '@tanstack/react-query'
import { getLogs } from '../api/logs.ts'

export function useGetLogs () {
  const { isLoading, data } = useQuery({
    queryKey: ['logs'],
    queryFn: ({ signal }) => getLogs(signal),
    staleTime: 1000 * 60 * 5,
  })

  const paginationData = data?.paginationData || null
  const logs = data?.data || null

  return {
    isLoading,
    logs,
    paginationData,
  }
}