import React from 'react'
import { Button, Col, FormControl, FormSelect, Modal, Row } from 'react-bootstrap'
import { CloseModal, useHideModal } from '../ui/AppModal.tsx'
import { useGetTemplateVarsByUuid } from '../../hooks/templates.ts'
import { EmailTemplateVars, EmailTestValues } from '../../types'
import { FormProvider, useFieldArray, useForm, useFormContext } from 'react-hook-form'
import { ControlledCodeMirror } from '../ui/CodeMirror.tsx'
import { FormLabel, FormRow } from '../ui/form/field.tsx'
import { MinusIcon, PlusIcon } from '../icons/icons.tsx'
import { ButtonWithLoading } from '../ui/ButtonWithLoading.tsx'
import { useMutation } from '@tanstack/react-query'
import { sendEmailTest } from '../../api/templates.ts'
import { toast } from 'react-toastify'
import { useUrl } from '../../contexts/UrlContext.tsx'
import { Preview } from '../preview/Preview.tsx'
import { useLocales } from '../../contexts/LocaleContext.tsx'

export function SendTest ({ uuid }: { uuid: string }) {
  const hideModal = useHideModal()
  const { isLoading, vars } = useGetTemplateVarsByUuid(uuid)

  return (
    <>
      <Modal.Header closeButton>
        <Modal.Title>Send Test</Modal.Title>
      </Modal.Header>
      {isLoading && <h2 className="py-4 px-2">Preparing Form...</h2>}
      {!isLoading && !!vars && <TestForm uuid={uuid} vars={vars}/>}
    </>
  )
}

type EmailItemType = {
  id: string,
  email: string
}
type TestValuesForm = {
  locale: string,
  vars: string,
  emails: EmailItemType[]
}

const firstEmailId = Date.now().toString()

function TestForm ({ uuid, vars }: { uuid: string, vars: EmailTemplateVars }) {
  const { locale } = useLocales()
  const form = useForm<TestValuesForm>({
    defaultValues: {
      locale,
      vars: vars.yaml,
      emails: [{ id: firstEmailId, email: '' }]
    }
  })

  const { isPending, mutateAsync } = useMutation({
    async mutationFn (data: EmailTestValues) {
      try {
        await sendEmailTest(uuid, data)
        toast.success('Email sent successfully!', { autoClose: 1500 })
      } catch (error) {
        toast.error('Ups, an error has occurred!', { autoClose: 2000 })
      }
    }
  })

  async function submit (formData: TestValuesForm) {
    const data = {
      ...formData,
      emails: formData.emails.map(item => item.email)
    }
    await mutateAsync(data)
  }

  return (
    <form onSubmit={form.handleSubmit(submit)}>
      <FormProvider {...form}>
        <Modal.Body>
          <Row>
            <Col xl={7} className="d-none d-xl-block">
              <PreviewTemplate uuid={uuid}/>
            </Col>
            <Col>
              <SelectLocale />
              <FormRow className="mb-3" name="vars">
                <FormLabel>Email Variables</FormLabel>
                <ControlledCodeMirror name="vars" type="yaml"/>
              </FormRow>
              <Recipients/>
            </Col>
          </Row>
        </Modal.Body>
        <Modal.Footer>
          <CloseModal>Close</CloseModal>
          <ButtonWithLoading type="submit" isLoading={isPending}>
            Send
          </ButtonWithLoading>
        </Modal.Footer>
      </FormProvider>
    </form>
  )
}

function Recipients () {
  const { register } = useFormContext()
  const { append, fields, remove } = useFieldArray({ name: 'emails' })

  return (
    <FormRow name="emails">
      <FormLabel>Recipients</FormLabel>
      {fields.map((emailItem, index) => (
        <article key={emailItem.id} className="d-flex gap-1 mb-2">
          <FormControl
            type="email"
            placeholder="email@domain.com"
            {...register(`emails.${index}.email`, { required: true })}
          />
          <Button variant="outline-dark" onClick={() => append({
            id: Date.now().toString(),
            email: '',
          })}><PlusIcon size={16}/></Button>
          <Button
            variant="outline-danger"
            onClick={() => remove(index)}
            disabled={fields.length <= 1}
          ><MinusIcon size={16}/></Button>
        </article>
      ))}
    </FormRow>
  )
}

function SelectLocale () {
  const { register } = useFormContext()
  const { locale, locales } = useLocales()

  return (
    <FormRow name="emails" className='mb-3'>
      <FormLabel>Locale</FormLabel>
      <FormSelect {...register('locale', { required: true })}>
        {locales.map(localeItem => (
          <option key={localeItem} value={localeItem}>{localeItem.toUpperCase()}</option>
        ))}
      </FormSelect>
    </FormRow>
  )
}

function PreviewTemplate ({ uuid }: { uuid: string }) {
  const { apiUrl } = useUrl()
  const previewUrl = `${apiUrl}/preview/template/${uuid}`

  return (
    <Preview url={previewUrl} minHeight="50vh"/>
  )
}