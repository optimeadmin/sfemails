import { useQuery } from '@tanstack/react-query'
import { getLogs } from '../api/logs.ts'

export function useGetLogs () {
  const { isLoading, data: logs } = useQuery({
    queryKey: ['logs'],
    queryFn: ({ signal }) => getLogs(signal),
    staleTime: 1000 * 60 * 5,
  })

  return {
    isLoading,
    logs,
  }
}