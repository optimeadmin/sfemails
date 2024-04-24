import { AxiosError, AxiosResponse } from 'axios'
import { FieldValues, UseFormSetError } from 'react-hook-form'

type ViolationItem = {
  propertyPath: string,
  title: string,
}

export function addServerError (axiosError: AxiosError, addError?: UseFormSetError<any>) {
  const { status, data } = axiosError.response as AxiosResponse

  if (!addError || status !== 422 || !('violations' in data)) return

  console.log(data.violations)

  data.violations.forEach(({ propertyPath, title }: ViolationItem) => {
    if (!propertyPath) return
    addError(propertyPath, { type: 'server', message: title })
  })
}