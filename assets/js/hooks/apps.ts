import { useQuery } from '@tanstack/react-query'
import { getEmailApps } from '../api/apps.ts'

export function useGetEmailApps () {
  const { isLoading, data: emailApps } = useQuery({
    queryKey: ['email-apps'],
    queryFn: ({ signal }) => getEmailApps(signal),
    staleTime: 1000 * 60 * 5,
  })

  return {
    isLoading,
    emailApps,
  }
}